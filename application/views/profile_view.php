<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/voting.css'); ?>">
    <!-- <form id="editProfileForm" action="<?php echo base_url('user/update_profile'); ?>" method="post" style="display:none;"> -->
    <?php $this->load->view('header', ['title' => 'Home Page']); ?>
    <style>
        /* Tab Styling */
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
        }
        .tab button:hover {
            background-color: #ddd;
        }
        .tab button.active {
            background-color: #ccc;
        }
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }
    </style>
</head>
<body>
<h1><?= htmlspecialchars($user->username) ?>'s Profile</h1>
    <div class="tab">
        <?php if ($is_own_profile): ?>
            <button class="tablinks" onclick="openTab(event, 'ProfileDetails')">Profile Details</button>
        <button class="tablinks" onclick="openTab(event, 'YourQuestions')">Your Questions</button>
    <?php else: ?>
        <button class="tablinks" onclick="openTab(event, 'ProfileDetails')">Profile Details</button>
        <button class="tablinks" onclick="openTab(event, 'YourQuestions')">Questions</button>
    <?php endif; ?>
    </div>

    <div id="ProfileDetails" class="tabcontent">
        <h3>Profile Details</h3>
        <!-- Display Flash Messages -->
        <?php if ($this->session->flashdata('error')): ?>
            <p class="flash-message" style="color: red;"><?php echo $this->session->flashdata('error'); ?></p>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            <p class="flash-message" style="color: green;"><?php echo $this->session->flashdata('success'); ?></p>
        <?php endif; ?>
        <div id="viewProfile">
        <p>Email: <?= htmlspecialchars($user->email); ?></p>
        <p>Joined Date: <?= date('F j, Y', strtotime($user->joined_date)); ?></p>
        <p>First Name: <?= htmlspecialchars($user->first_name); ?></p>
        <p>Last Name: <?= htmlspecialchars($user->last_name); ?></p>
        <p>Mobile Number: <?= htmlspecialchars($user->mobile); ?></p>
        <p>Address: <?= htmlspecialchars($user->address); ?></p>

        <?php if ($is_own_profile): ?>
            <button onclick="toggleEditForm()">Edit</button>
        <?php endif; ?>
    </div>
        
        <form id="editProfileForm" action="<?php echo base_url('user/update_profile'); ?>" method="post" style="display:none;">
        <!-- Adding fields for username and email in the edit form -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user->username); ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>" required>
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user->first_name); ?>">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user->last_name); ?>">
            <label for="mobile">Mobile Number:</label>
            <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($user->mobile); ?>">
            <label for="address">Address:</label>
            <textarea id="address" name="address"><?php echo htmlspecialchars($user->address); ?></textarea>
            <button type="submit">Save Changes</button>
            <button type="button" onclick="toggleEditForm()">Cancel</button>
        </form>
    </div>

    <div id="YourQuestions" class="tabcontent">
    <?php if ($is_own_profile): ?>
        <h3>Your Questions</h3>
    <?php else: ?>
        <h3>Questions</h3>
    <?php endif; ?>
        <?php if (!empty($questions)): ?>
            <ul>
                <?php foreach ($questions as $question): ?>
                    <p id="question_<?= $question->id ?>">
                    <a href="<?php echo base_url('question/details/' . $question->id); ?>">
                        <?php echo htmlspecialchars($question->title); ?>
                    </a>
                    <?php if ($is_own_profile): ?>
                        <button onclick="deleteQuestion(<?= $question->id; ?>)">Delete</button>
        <?php endif; ?>
                </p>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No questions posted yet.</p>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            setTimeout(clearMessages, 10000); // Clear messages after 10 seconds
        });

        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";

            // Clear messages when tab is switched
            // clearMessages();
        }

        function clearMessages() {
            const messages = document.querySelectorAll('.flash-message');
            messages.forEach(msg => {
                msg.style.display = 'none';
            });
        }

        // Set the default tab
        document.getElementsByClassName('tablinks')[0].click();

        function toggleEditForm() {
            var form = document.getElementById('editProfileForm');
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        function deleteQuestion(questionId) {
            if (!confirm('Are you sure you want to delete this question?')) {
                return; // Stop the function if the user cancels.
            }

            $.ajax({
                url: '<?= base_url("question/delete/"); ?>' + questionId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Remove the question element from the page
                        $('#question_' + questionId).remove();
                        alert('Question deleted successfully!');
                    } else {
                        alert('Failed to delete the question.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }

    </script>
</body>
</html>
