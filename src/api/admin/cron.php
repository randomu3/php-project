<?php
/**
 * Admin Cron API
 * 
 * Cron jobs and email queue management
 * 
 * @package AuraUI\API\Admin
 */

require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$db = getDB();
$db->exec("SET NAMES utf8mb4");

switch ($action) {
    // Cron jobs
    case 'get_jobs':
        getJobs($db);
        break;
    case 'create_job':
        createJob($db);
        break;
    case 'update_job':
        updateJob($db);
        break;
    case 'delete_job':
        deleteJob($db, (int)($_POST['id'] ?? 0));
        break;
    case 'toggle_job':
        toggleJob($db, (int)($_POST['id'] ?? 0));
        break;
    case 'run_job':
        runJob($db, (int)($_POST['id'] ?? 0));
        break;
    case 'get_job_logs':
        getJobLogs($db, (int)($_GET['job_id'] ?? 0));
        break;
    // Email queue
    case 'get_queue':
        getEmailQueue($db);
        break;
    case 'queue_email':
        queueEmail($db);
        break;
    case 'process_queue':
        processQueue($db);
        break;
    case 'delete_queued':
        deleteQueued($db, (int)($_POST['id'] ?? 0));
        break;
    case 'queue_stats':
        getQueueStats($db);
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

/**
 * Get all cron jobs
 */
function getJobs($db): void
{
    $stmt = $db->query("SELECT * FROM cron_jobs ORDER BY name");
    echo json_encode(['success' => true, 'jobs' => $stmt->fetchAll()]);
}

/**
 * Create cron job
 */
function createJob($db): void
{
    $name = trim($_POST['name'] ?? '');
    $command = trim($_POST['command'] ?? '');
    $schedule = trim($_POST['schedule'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if (empty($name) || empty($command) || empty($schedule)) {
        echo json_encode(['success' => false, 'error' => 'Name, command and schedule required']);
        return;
    }
    
    $stmt = $db->prepare("
        INSERT INTO cron_jobs (name, command, schedule, description)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$name, $command, $schedule, $description]);
    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
}

/**
 * Update cron job
 */
function updateJob($db): void
{
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $command = trim($_POST['command'] ?? '');
    $schedule = trim($_POST['schedule'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    $stmt = $db->prepare("
        UPDATE cron_jobs SET name = ?, command = ?, schedule = ?, description = ?
        WHERE id = ?
    ");
    $stmt->execute([$name, $command, $schedule, $description, $id]);
    echo json_encode(['success' => true]);
}

/**
 * Delete cron job
 */
function deleteJob($db, int $id): void
{
    $stmt = $db->prepare("DELETE FROM cron_jobs WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
}

/**
 * Toggle job active status
 */
function toggleJob($db, int $id): void
{
    $stmt = $db->prepare("UPDATE cron_jobs SET is_active = NOT is_active WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
}

/**
 * Run job manually
 */
function runJob($db, int $id): void
{
    $stmt = $db->prepare("SELECT * FROM cron_jobs WHERE id = ?");
    $stmt->execute([$id]);
    $job = $stmt->fetch();
    
    if (!$job) {
        echo json_encode(['success' => false, 'error' => 'Job not found']);
        return;
    }
    
    $startTime = microtime(true);
    
    // Update status to running
    $db->prepare("UPDATE cron_jobs SET last_status = 'running' WHERE id = ?")->execute([$id]);
    
    // Execute command
    $output = [];
    $returnCode = 0;
    exec($job['command'] . ' 2>&1', $output, $returnCode);
    
    $duration = (int)((microtime(true) - $startTime) * 1000);
    $outputStr = implode("\n", $output);
    $status = $returnCode === 0 ? 'success' : 'failed';
    
    // Update job
    $stmt = $db->prepare("
        UPDATE cron_jobs SET 
            last_run_at = NOW(), 
            last_status = ?, 
            last_output = ?, 
            last_duration_ms = ?,
            run_count = run_count + 1,
            fail_count = fail_count + ?
        WHERE id = ?
    ");
    $stmt->execute([$status, $outputStr, $duration, $status === 'failed' ? 1 : 0, $id]);
    
    // Log execution
    $stmt = $db->prepare("
        INSERT INTO cron_job_logs (job_id, status, output, duration_ms, finished_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$id, $status, $outputStr, $duration]);
    
    echo json_encode([
        'success' => true, 
        'status' => $status, 
        'output' => $outputStr,
        'duration' => $duration
    ]);
}

/**
 * Get job execution logs
 */
function getJobLogs($db, int $jobId): void
{
    $stmt = $db->prepare("
        SELECT * FROM cron_job_logs 
        WHERE job_id = ? 
        ORDER BY started_at DESC 
        LIMIT 50
    ");
    $stmt->execute([$jobId]);
    echo json_encode(['success' => true, 'logs' => $stmt->fetchAll()]);
}

/**
 * Get email queue
 */
function getEmailQueue($db): void
{
    $status = $_GET['status'] ?? '';
    
    $sql = "SELECT q.*, t.name as template_name FROM email_queue q LEFT JOIN email_templates t ON q.template_id = t.id";
    $params = [];
    
    if (!empty($status)) {
        $sql .= " WHERE q.status = ?";
        $params[] = $status;
    }
    
    $sql .= " ORDER BY q.priority DESC, q.created_at ASC LIMIT 100";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    echo json_encode(['success' => true, 'queue' => $stmt->fetchAll()]);
}

/**
 * Add email to queue
 */
function queueEmail($db): void
{
    $toEmail = trim($_POST['to_email'] ?? '');
    $toName = trim($_POST['to_name'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $body = $_POST['body'] ?? '';
    $priority = (int)($_POST['priority'] ?? 0);
    $scheduledAt = $_POST['scheduled_at'] ?? null;
    
    if (empty($toEmail) || empty($subject) || empty($body)) {
        echo json_encode(['success' => false, 'error' => 'Email, subject and body required']);
        return;
    }
    
    $stmt = $db->prepare("
        INSERT INTO email_queue (to_email, to_name, subject, body, priority, scheduled_at)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$toEmail, $toName, $subject, $body, $priority, $scheduledAt]);
    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
}

/**
 * Process email queue
 */
function processQueue($db): void
{
    $limit = (int)($_POST['limit'] ?? 10);
    
    $stmt = $db->prepare("
        SELECT * FROM email_queue 
        WHERE status = 'pending' 
        AND (scheduled_at IS NULL OR scheduled_at <= NOW())
        AND attempts < max_attempts
        ORDER BY priority DESC, created_at ASC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    $emails = $stmt->fetchAll();
    
    $sent = 0;
    $failed = 0;
    
    foreach ($emails as $email) {
        // Mark as processing
        $db->prepare("UPDATE email_queue SET status = 'processing' WHERE id = ?")->execute([$email['id']]);
        
        // Try to send
        $result = sendEmail($email['to_email'], $email['subject'], $email['body']);
        
        if ($result) {
            $db->prepare("UPDATE email_queue SET status = 'sent', sent_at = NOW() WHERE id = ?")->execute([$email['id']]);
            $sent++;
        } else {
            $newAttempts = $email['attempts'] + 1;
            $newStatus = $newAttempts >= $email['max_attempts'] ? 'failed' : 'pending';
            $db->prepare("UPDATE email_queue SET status = ?, attempts = ?, error_message = ? WHERE id = ?")
               ->execute([$newStatus, $newAttempts, 'Send failed', $email['id']]);
            $failed++;
        }
        
        usleep(100000); // 0.1s delay
    }
    
    echo json_encode(['success' => true, 'sent' => $sent, 'failed' => $failed]);
}

/**
 * Delete queued email
 */
function deleteQueued($db, int $id): void
{
    $stmt = $db->prepare("DELETE FROM email_queue WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
}

/**
 * Get queue statistics
 */
function getQueueStats($db): void
{
    $stats = $db->query("
        SELECT 
            status,
            COUNT(*) as count
        FROM email_queue
        GROUP BY status
    ")->fetchAll();
    
    $byDay = $db->query("
        SELECT DATE(created_at) as date, COUNT(*) as count
        FROM email_queue
        WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date
    ")->fetchAll();
    
    echo json_encode(['success' => true, 'by_status' => $stats, 'by_day' => $byDay]);
}
