<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/home.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/voting.css'); ?>">
    <title>Home Page</title>
    <?php $this->load->view('header', ['title' => 'Home']); ?>
</head>
<body>
    <div class="home-view-top">
    <div class="sort-drop">
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
            <button class="add-question" id="openModal">Ask a Question</button>
            <!-- Modal -->
            <div id="questionModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Ask a Question</h2>
                    <form id="questionForm">
                        <label for="title">Question Title:</label>
                        <input type="text" id="title" name="title" required><br>
                        <label for="description">Question Description:</label>
                        <textarea id="description" name="description" required></textarea><br>
                        <div class="modal-actions">
                        <button type="submit">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        <?php endif; ?>
    </div>
    </div>
    <div>
    </div>
    <div class="question-wrapper">
    <?php if ($logged_in): ?>
        <?php foreach($questions as $question): ?>
        <div class="question-card">
            <div class="question-card-title">
                <a href="<?php echo base_url('question/details/' . $question->id); ?>">
                    <?php echo $question->title; ?>
                </a>
            </div>
            <hr>
            <div class="question-card-info">
                <p class="username">Asked  <a href="<?php echo base_url('user/profile/' . $question->user_id); ?>"><i class="fas fa-at"></i> <?php echo $question->username; ?></a></p>
                <p  class="calender"><i class="fas fa-calendar"></i> <?php echo date('F j, Y, h:i A', strtotime($question->created_at)); ?></p>
            </div>
            <p class="question-desc"><?php echo $question->description; ?></p>
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
            <div class="question-card-title"><?php echo $question->title; ?></div>
            <hr>
            <div class="question-card-info">
                <p class="username">Asked  <i class="fas fa-at"></i>  <a href="<?php echo base_url('login'); ?>"><?php echo $question->username; ?></a></p>
                <p class="calender"><i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($question->created_at)); ?></p>
            </div>
            <p class="question-desc"><?php echo $question->description; ?></p>
            <div class="question-interaction-comp">
                <div class="question-card-vote">
                        <span href="<?php echo base_url('login'); ?>" class="upvote-group <?= isset($user_votes[$question->id]) && $user_votes[$question->id] == 'up' ? 'active' : '' ?>" id="upvote_group_<?php echo $question->id; ?>"><a href="#" class="upvote" onclick="upvoteQuestion(<?php echo $question->id; ?>); return false;"><i class="fas fa-arrow-up"></i></a><span id="upvotes_<?php echo $question->id; ?>"><?php echo $question->upvotes; ?></span></span>
                        <span href="<?php echo base_url('login'); ?>" class="downvote-group <?= isset($user_votes[$question->id]) && $user_votes[$question->id] == 'down' ? 'active' : '' ?>" id="downvote_group_<?php echo $question->id; ?>"><a href="#" class="downvote" onclick="downvoteQuestion(<?php echo $question->id; ?>); return false;"><i class="fas fa-arrow-down"></i></a><span id="downvotes_<?php echo $question->id; ?>"><?php echo $question->downvotes; ?></span></span>
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
                    $('#questionForm')[0].reset();
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert('Failed to submit question. Please try again.');
            }
        });
    });

    // Reset form when closing the modal
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
            handleVote(response, questionId);
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
            handleVote(response, questionId);
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error);
        }
    });
}

        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('questionModal');
            var btn = document.getElementById('openModal');
            var span = document.getElementsByClassName("close")[0];
            btn.onclick = function() {
                modal.style.display = "block";
            }
            span.onclick = function() {
                modal.style.display = "none";
            }
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
            var formData = $(formElement).serialize();

            $.ajax({
                url: '<?php echo base_url('question/post_comment'); ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var username = "Your Username";
                        var newCommentHtml = '<div class="comment"><p><strong>' + username + ':</strong> ' + $(formElement).find('textarea[name="comment"]').val() + '</p></div>';
                        $(formElement).closest('.question-card').find('#comments').append(newCommentHtml);
                        $(formElement).find('textarea[name="comment"]').val('');
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
