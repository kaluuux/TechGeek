<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/voting.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/profile.css'); ?>">
    <!-- <form id="editProfileForm" action="<?php echo base_url('user/update_profile'); ?>" method="post" style="display:none;"> -->
    <?php $this->load->view('header', ['title' => 'Profile']); ?>
</head>
<body>
    <div class="user-profile">
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
        <div class="profile-details-wrapper">
            <?php if ($this->session->flashdata('error')): ?>
                <p class="flash-message alert-danger" style="color: red;"><?php echo $this->session->flashdata('error'); ?></p>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')): ?>
                <p class="flash-message alert-success" style="color: green;"><?php echo $this->session->flashdata('success'); ?></p>
            <?php endif; ?>
            <div id="viewProfile">
                <p><span>Email:</span>  <?= htmlspecialchars($user->email); ?></p>
                <p><span>Joined Date:</span>  <?= date('F j, Y', strtotime($user->joined_date)); ?></p>
                <p><span>First Name:</span>  <?= htmlspecialchars($user->first_name); ?></p>
                <p><span>Last Name:</span>  <?= htmlspecialchars($user->last_name); ?></p>
                <p><span>Mobile Number:</span>  <?= htmlspecialchars($user->mobile); ?></p>
                <p><span>Address:</span>  <?= htmlspecialchars($user->address); ?></p>

                <?php if ($is_own_profile): ?>
                    <button class="edit-profile-btn" onclick="toggleEditForm()">Edit</button>
                <?php endif; ?>
            </div>
        </div>
        <!-- Modal Backdrop -->
<div id="modalBackdrop" class="modal-backdrop" onclick="toggleEditForm()" style="display:none;"></div>

<!-- Modal Content -->
<div id="editProfileModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h3>Edit Profile</h3>
        <form id="editProfileForm" action="<?php echo base_url('user/update_profile'); ?>" method="post">
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
            <div class="modal-actions">
                <button type="submit" class="edit-profile-btn-save">Save Changes</button>
                <button type="button" onclick="toggleEditForm()" class="edit-profile-btn-cancel">Cancel</button>
            </div>
        </form>
    </div>
</div>

    </div>

    <div id="YourQuestions" class="tabcontent">
    <!-- <?php if ($is_own_profile): ?>
        <h3>Your Questions</h3>
    <?php else: ?>
        <h3>Questions</h3>
    <?php endif; ?> -->
        <?php if (!empty($questions)): ?>
            <div class="profile-question-wrap">
                <?php foreach ($questions as $question): ?>
                    <p class="profile-question-card" id="question_<?= $question->id ?>">
                    <a href="<?php echo base_url('question/details/' . $question->id); ?>">
                        <?php echo htmlspecialchars($question->title); ?>
                    </a>
                    <?php if ($is_own_profile): ?>
                        <button onclick="deleteQuestion(<?= $question->id; ?>)">Delete</button>
        <?php endif; ?>
                </p>
                <?php endforeach; ?>
                    </div>
        <?php else: ?>
            <p>No questions posted yet.</p>
        <?php endif; ?>
    </div>
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

        function toggleEditForm() {
    var modal = document.getElementById('editProfileModal');
    var backdrop = document.getElementById('modalBackdrop');
    if (modal.style.display === 'none') {
        modal.style.display = 'block';
        backdrop.style.display = 'block';
    } else {
        modal.style.display = 'none';
        backdrop.style.display = 'none';
    }
}

    </script>
</body>
</html>
