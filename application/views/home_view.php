<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/home.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/voting.css'); ?>">
    <title>Home Page</title>
    <?php $this->load->view('header', ['title' => 'Home Page']); ?>
    <style>
        .upvote-group.active, .downvote-group.active {
        background-color: #4CAF50;
        color: white;
        }

        .upvote-group:hover,
        .downvote-group:hover {
            background-color: #cacaca;
        }

        .upvote-group a, .downvote-group a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
    <div class="home-view-top">
    <div>
        <select id="sortQuestions" onchange="sortQuestions()">
            <option value="recent" <?php echo ($sort == 'recent') ? 'selected' : ''; ?>>Most Recent</option>
            <option value="most_upvotes" <?php echo ($sort == 'most_upvotes') ? 'selected' : ''; ?>>Most Upvotes</option>
            <option value="most_downvotes" <?php echo ($sort == 'most_downvotes') ? 'selected' : ''; ?>>Most Downvotes</option>
            <option value="most_views" <?php echo ($sort == 'most_views') ? 'selected' : ''; ?>>Most Views</option>
        </select>


    </div>
    <form class="search-form" action="<?php echo base_url('home'); ?>" method="get">
        <input type="text" name="search_query" placeholder="Search questions by title or username" value="<?php echo $this->input->get('search_query'); ?>">
        <button type="submit">Search</button>
    </form>
    <div class="add-question">
        <?php if ($logged_in): ?>
            <button id="openModal">Ask a Question</button>
            <!-- Modal -->
            <div id="questionModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <!-- <form action="<?php echo base_url('question/add'); ?>" method="post">
                        <label for="title">Question Title:</label><br>
                        <input type="text" id="title" name="title" required><br>
                        <label for="description">Question Description:</label><br>
                        <textarea id="description" name="description" rows="4" required></textarea><br>
                        <button type="submit">Submit</button>
                    </form> -->
                    <form id="questionForm">
    <label for="title">Question Title:</label>
    <input type="text" id="title" name="title" required><br>
    <label for="description">Question Description:</label>
    <textarea id="description" name="description" required></textarea><br>
    <button type="submit">Submit</button>
</form>

                </div>
            </div>
        <?php else: ?>
            <!-- Prompt to Log In -->
            <p>Please <a href="<?php echo base_url('login'); ?>">login</a> to post a question.</p>
        <?php endif; ?>
    </div>
    </div>
    <div>
    <h1>Recent Questions</h1>
    <!-- Button to open modal -->
    </div>
    <div>
    <?php if ($logged_in): ?>
        <?php foreach($questions as $question): ?>
        <div class="question-card">
            <div class="question-card-title">
                <a href="<?php echo base_url('question/details/' . $question->id); ?>">
                    <?php echo $question->title; ?>
                </a>
                <p><a href="<?php echo base_url('user/profile/' . $question->user_id); ?>"><i class="fas fa-at"></i> <?php echo $question->username; ?></a></p>
                <p>Date: <?php echo date('F j, Y', strtotime($question->created_at)); ?></p>
            </div>
            <div class="question-interaction-comp">
                <div class="question-card-vote">
                    <span class="upvote-group <?= isset($user_votes[$question->id]) && $user_votes[$question->id] == 'up' ? 'active' : '' ?>" id="upvote_group_<?php echo $question->id; ?>"><a href="#" class="upvote" onclick="upvoteQuestion(<?php echo $question->id; ?>); return false;"><i class="fas fa-arrow-up"></i></a><span id="upvotes_<?php echo $question->id; ?>"><?php echo $question->upvotes; ?></span></span>
                    <span class="downvote-group <?= isset($user_votes[$question->id]) && $user_votes[$question->id] == 'down' ? 'active' : '' ?>" id="downvote_group_<?php echo $question->id; ?>"><a href="#" class="downvote" onclick="downvoteQuestion(<?php echo $question->id; ?>); return false;"><i class="fas fa-arrow-down"></i></a><span id="downvotes_<?php echo $question->id; ?>"><?php echo $question->downvotes; ?></span></span>
                </div>
                <div id="comments">
                    <p><i class="far fa-comment"></i> <?php echo isset($question->comment_count) ? $question->comment_count : '0'; ?></p>
                </div>

                <div class="question-card-views">
                    <p><i class="far fa-eye"></i> <?php echo $question->view_count; ?></p>
                </div>
            </div>
        </div>

        <?php endforeach; ?>
        <?php else: ?>
        <?php foreach($questions as $question): ?>
        <div class="question-card">
        <p>Asked by: <?php echo $question->username; ?></p>
            <div class="question-card-title"><?php echo $question->title; ?></div>
            <div class="question-card-vote">
                <span class="upvote-group"><a href="<?php echo base_url('login'); ?>" class="upvote"><i class="fas fa-arrow-up">Up-Vote</i></a><span id="upvotes_<?php echo $question->id; ?>"><?php echo $question->upvotes; ?></span></span>
                <span class="downvote-group"><a href="<?php echo base_url('login'); ?>" class="downvote"><i class="fas fa-arrow-down">Down-Vote</i></a><span id="downvotes_<?php echo $question->id; ?>"><?php echo $question->downvotes; ?></span></span>
            </div>
            <a href="<?php echo base_url('login'); ?>">View Question</a>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
            </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>



$(document).ready(function() {
    $('#questionForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: '<?= base_url("api/post_question"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Question added successfully');
                    document.getElementById('questionModal').style.display = 'none';
                    window.location.reload();
                    $('#questionForm')[0].reset(); // Reset the form fields
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert('Failed to submit question. Please try again.');
            }
        });
    });

    // Reset form when closing the modal with the X button or clicking outside the modal
    var modal = document.getElementById('questionModal');
    var btn = document.getElementById('openModal');
    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function() {
        modal.style.display = "block";
    };

    span.onclick = function() {
        modal.style.display = "none";
        $('#questionForm')[0].reset();
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            $('#questionForm')[0].reset();
        }
    };
});



function upvoteQuestion(questionId) {
    $.ajax({
        url: '<?= base_url("question/upvote/"); ?>' + questionId,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            handleVote(response, questionId); // Call handleVote here
        },
        error: function(xhr, status, error) {
            alert('Error: ' + xhr.responseText);
        }
    });
}

function downvoteQuestion(questionId) {
    $.ajax({
        url: '<?= base_url("question/downvote/"); ?>' + questionId,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            handleVote(response, questionId); // Call handleVote here
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error);
        }
    });
}

        document.addEventListener('DOMContentLoaded', function() {
            // Get the modal
            var modal = document.getElementById('questionModal');

            // Get the button that opens the modal
            var btn = document.getElementById('openModal');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks the button, open the modal 
            btn.onclick = function() {
                modal.style.display = "block";
            }

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });

        function sortQuestions() {
            var sortValue = document.getElementById('sortQuestions').value;
            window.location.href = '<?php echo base_url('home?sort='); ?>' + sortValue;
        }

        function postComment(event, formElement) {
            event.preventDefault(); // Prevent the default form submit action
            var formData = $(formElement).serialize(); // Serialize the data from the specific form

            $.ajax({
                url: '<?php echo base_url('question/post_comment'); ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var username = "Your Username"; // Assuming you have the username available as discussed previously
                        var newCommentHtml = '<div class="comment"><p><strong>' + username + ':</strong> ' + $(formElement).find('textarea[name="comment"]').val() + '</p></div>';
                        $(formElement).closest('.question-card').find('#comments').append(newCommentHtml);
                        $(formElement).find('textarea[name="comment"]').val(''); // Clear the textarea
                        alert('Comment posted!');
                    } else {
                        alert('Error: ' + (response.error || 'Unknown error'));
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }

        function handleVote(response, questionId) {
            if (response.error) {
                alert(response.error);
            } else {
                $('#upvotes_' + questionId).text(response.upvotes);
                $('#downvotes_' + questionId).text(response.downvotes);
                // Update the UI based on the current vote
                switch(response.currentVote) {
                    case 'up':
                        $('#upvote_group_' + questionId).addClass('active');
                        $('#downvote_group_' + questionId).removeClass('active');
                        break;
                    case 'down':
                        $('#downvote_group_' + questionId).addClass('active');
                        $('#upvote_group_' + questionId).removeClass('active');
                        break;
                    default:
                        $('#upvote_group_' + questionId).removeClass('active');
                        $('#downvote_group_' + questionId).removeClass('active');
                        break;
                }
            }
        }


    </script>

</body>
</html>
