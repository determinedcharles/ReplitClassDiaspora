
<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/helpers.php';

requireAuth();

$user = getCurrentUser();
$role = $_SESSION['user_role'];

// Load messages from messages.json
$messages = loadJsonData('data/messages.json');

// Filter messages for current user
$userMessages = array_filter($messages, function($message) use ($user) {
    return $message['sender_id'] === $user['id'] || $message['recipient_id'] === $user['id'];
});

// Sort messages by date (newest first)
usort($userMessages, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - ClassDiaspora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .max-container { max-width: 680px; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include 'includes/header.php'; ?>

    <div class="max-container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6" data-aos="fade-up">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Messages</h1>
                    <p class="text-gray-600">Your communication hub</p>
                </div>
                <button onclick="composeMessage()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>New Message
                </button>
            </div>

            <!-- Message Filters -->
            <div class="flex space-x-2 mb-6">
                <button class="filter-btn active bg-blue-600 text-white px-4 py-2 rounded-lg text-sm" data-filter="all">
                    All Messages
                </button>
                <button class="filter-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300" data-filter="unread">
                    Unread (3)
                </button>
                <button class="filter-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300" data-filter="sent">
                    Sent
                </button>
            </div>
        </div>

        <!-- Messages List -->
        <div class="space-y-4" id="messagesList">
            <?php if (!empty($userMessages)): ?>
                <?php foreach ($userMessages as $message): ?>
                    <div class="message-item bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow" data-aos="fade-up">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    <?php 
                                    $otherUserId = ($message['sender_id'] === $user['id']) ? $message['recipient_id'] : $message['sender_id'];
                                    $otherUserName = ($message['sender_id'] === $user['id']) ? $message['recipient_name'] : $message['sender_name'];
                                    echo strtoupper(substr($otherUserName, 0, 1)); 
                                    ?>
                                </div>
                                <div class="ml-3">
                                    <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($otherUserName) ?></h3>
                                    <p class="text-sm text-gray-600">
                                        <?= ($message['sender_id'] === $user['id']) ? 'You' : htmlspecialchars($message['sender_name']) ?>
                                        • <?= date('M j, Y g:i A', strtotime($message['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <?php if ($message['status'] === 'unread' && $message['recipient_id'] === $user['id']): ?>
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">
                                        New
                                    </span>
                                <?php endif; ?>
                                <button onclick="toggleMessage('msg_<?= $message['id'] ?>')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-chevron-down" id="icon_msg_<?= $message['id'] ?>"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h4 class="font-medium text-gray-800 mb-2"><?= htmlspecialchars($message['subject']) ?></h4>
                            <p class="text-gray-600 text-sm line-clamp-2"><?= htmlspecialchars(substr($message['content'], 0, 100)) ?>...</p>
                        </div>

                        <!-- Expanded Message Content -->
                        <div id="msg_<?= $message['id'] ?>" class="hidden mt-4 pt-4 border-t border-gray-200">
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <p class="text-gray-700"><?= nl2br(htmlspecialchars($message['content'])) ?></p>
                            </div>
                            
                            <div class="flex space-x-2">
                                <?php if ($message['sender_id'] !== $user['id']): ?>
                                    <button onclick="replyToMessage('<?= $message['id'] ?>', '<?= htmlspecialchars($message['sender_name']) ?>', '<?= htmlspecialchars($message['subject']) ?>')" 
                                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                        <i class="fas fa-reply mr-2"></i>Reply
                                    </button>
                                <?php endif; ?>
                                <button onclick="deleteMessage('<?= $message['id'] ?>')" 
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm">
                                    <i class="fas fa-trash mr-2"></i>Delete
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-envelope text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Messages Yet</h3>
                    <p class="text-gray-500 mb-6">Start connecting with teachers and parents by sending your first message.</p>
                    <button onclick="composeMessage()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Send Your First Message
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => {
                    b.classList.remove('active', 'bg-blue-600', 'text-white');
                    b.classList.add('bg-gray-200', 'text-gray-700');
                });
                this.classList.add('active', 'bg-blue-600', 'text-white');
                this.classList.remove('bg-gray-200', 'text-gray-700');
            });
        });

        function toggleMessage(messageId) {
            const messageContent = document.getElementById(messageId);
            const icon = document.getElementById('icon_' + messageId);
            
            if (messageContent.classList.contains('hidden')) {
                messageContent.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                messageContent.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }

        function composeMessage() {
            Swal.fire({
                title: 'Compose New Message',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">To</label>
                            <input type="text" id="messageRecipient" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter recipient name or email">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" id="messageSubject" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter subject">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea id="messageContent" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Write your message here..."></textarea>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Send Message',
                confirmButtonColor: '#2563eb',
                width: '600px',
                preConfirm: () => {
                    const recipient = document.getElementById('messageRecipient').value;
                    const subject = document.getElementById('messageSubject').value;
                    const content = document.getElementById('messageContent').value;
                    
                    if (!recipient || !subject || !content) {
                        Swal.showValidationMessage('Please fill in all fields');
                        return false;
                    }
                    
                    return { recipient, subject, content };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Message Sent!',
                        text: 'Your message has been sent successfully',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        function replyToMessage(messageId, senderName, originalSubject) {
            Swal.fire({
                title: `Reply to ${senderName}`,
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" id="replySubject" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="Re: ${originalSubject}">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea id="replyContent" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Write your reply here..."></textarea>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Send Reply',
                confirmButtonColor: '#2563eb',
                width: '600px',
                preConfirm: () => {
                    const content = document.getElementById('replyContent').value;
                    
                    if (!content) {
                        Swal.showValidationMessage('Please write a reply');
                        return false;
                    }
                    
                    return { content };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Reply Sent!',
                        text: 'Your reply has been sent successfully',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    });
                }
            });
        }

        function deleteMessage(messageId) {
            Swal.fire({
                title: 'Delete Message?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Message Deleted',
                        text: 'The message has been deleted',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }
    </script>
</body>
</html>
