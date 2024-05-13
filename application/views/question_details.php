<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/voting.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/question_details.css'); ?>">
    <?php $this->load->view('header', ['title' => 'Question']); ?>
    <title>Question Details</title>
</head>

<body>
    <div class="question-details-container">
        <div class="question-details-container-top">
            <h2 class="question-detail-title"><?php echo $question->title; ?></h2>
            <hr>
            <div class="question-meta-data">
                <p><span>Author: </span><a href="<?php echo base_url('user/profile/' . $question->user_id); ?>"><i class="fas fa-at"></i><?php echo $question->username; ?></a></p>
                <p><i class="fas fa-calendar"></i> <?php echo date('F j, Y, h:i A', strtotime($question->created_at)); ?></p>
                <p><i class="far fa-eye"></i> <?php echo $question->view_count; ?></p>
            </div>
            <div class="question-details-desc"></div>
            <p class="desc"><?php echo $question->description; ?></p>
        </div>
        <hr style="margin-top: 60px; margin-bottom: 10px;">
        <h3 class="comment-count-profile">Answers: <span id="comment_count"><?= $comment_count; ?></span></h3>

        <div id="comments">
            <?php if (!empty($comments)) : ?>
                <?php foreach ($comments as $comment) : ?>
                    <div id="comment_<?= $comment->id ?>" class="comment">
                        <p class="question-detail-comment">
                            <span class="question-detail-comment-username" style="font-weight: 700;"><i class="far fa-user"></i> <?php echo $comment->username; ?> </span>
                            <span><?php echo htmlspecialchars($comment->comment); ?></span>
                        </p>
                        <div class="comment-vote">
                            <a href="#" class="upvote-comment" onclick="upvoteComment(<?= $comment->id; ?>); return false;">
                                <i class="fas fa-thumbs-up"></i>
                            </a>
                            <span id="upvotes_comment_<?= $comment->id; ?>"><?= $comment->upvotes; ?></span>
                            <a href="#" class="downvote-comment" onclick="downvoteComment(<?= $comment->id; ?>); return false;">
                                <i class="fas fa-thumbs-down"></i>
                            </a>
                            <span id="downvotes_comment_<?= $comment->id; ?>"><?= $comment->downvotes; ?></span>
                        </div>

                        <?php if ($comment->user_id == $this->session->userdata('user_id')) : ?>
                            <button class="delete-comment" onclick="deleteComment(<?= $comment->id; ?>)">Delete</button>
                        <?php endif; ?>
                    </div>

                <?php endforeach; ?>
            <?php else : ?>
                <p class="no-comments">No answers yet.</p>
            <?php endif; ?>
        </div>

        <!-- Add comment form -->
        <form id="commentForm">
            <input type="hidden" id="question_id" name="question_id" value="<?php echo $question->id; ?>">
            <textarea class="add-comment-area" id="comment" name="comment" required></textarea>
            <button class="post-comment" type="button" onclick="postComment()">Post Comment</button>
        </form>
        <hr style="margin-top: 60px; margin-bottom: 10px;">
        <div class="question-card-vote">
            <span class="upvote-group <?= $question->user_vote == 'up' ? 'active' : '' ?>" id="upvote_group_<?= $question->id; ?>">
                <a href="#" class="upvote" onclick="upvoteQuestion(<?= $question->id; ?>); return false;">
                    <i class="fas fa-arrow-up"></i>
                </a>
                <span id="upvotes_<?= $question->id; ?>"><?= $question->upvotes; ?></span>
            </span>
            <span class="downvote-group <?= $question->user_vote == 'down' ? 'active' : '' ?>" id="downvote_group_<?= $question->id; ?>">
                <a href="#" class="downvote" onclick="downvoteQuestion(<?= $question->id; ?>); return false;">
                    <i class="fas fa-arrow-down"></i>
                </a>
                <span id="downvotes_<?= $question->id; ?>"><?= $question->downvotes; ?></span>
            </span>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function handleVote(response, questionId) {
            $('#upvotes_' + questionId).text(response.upvotes);
            $('#downvotes_' + questionId).text(response.downvotes);
            if (response.currentVote === 'up') {
                $('#upvote_group_' + questionId).addClass('active');
                $('#downvote_group_' + questionId).removeClass('active');
            } else if (response.currentVote === 'down') {
                $('#downvote_group_' + questionId).addClass('active');
                $('#upvote_group_' + questionId).removeClass('active');
            } else {
                $('#upvote_group_' + questionId).removeClass('active');
                $('#downvote_group_' + questionId).removeClass('active');
            }
        }

        function upvoteQuestion(questionId) {
            $.ajax({
                url: '<?= base_url("question/upvote/"); ?>' + questionId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    handleVote(response, questionId);
                },
                error: function(xhr) {
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
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }

        function postComment() {
            var questionId = $('#question_id').val();
            var comment = $('#comment').val();

            $.ajax({
                url: '<?php echo base_url('question/post_comment'); ?>',
                type: 'POST',
                data: {
                    question_id: questionId,
                    comment: comment
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + (response.error || 'Unknown error'));
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }

        function deleteComment(commentId) {
            if (!confirm('Are you sure you want to delete this comment?')) {
                return;
            }
            $.ajax({
                url: '<?= base_url("question/delete_comment/"); ?>' + commentId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#comment_' + commentId).remove();
                        $('#comment_count').text(response.new_comment_count);
                    } else {
                        alert('Failed to delete comment: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }

        function handleVoteUpdate(commentId, response) {
            if (response.upvotes != undefined && response.downvotes != undefined) {
                $('#upvotes_comment_' + commentId).text(response.upvotes);
                $('#downvotes_comment_' + commentId).text(response.downvotes);
            }
        }

        function upvoteComment(commentId) {
            $.ajax({
                url: '<?= base_url("question/upvote_comment/"); ?>' + commentId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    handleVoteUpdate(commentId, response);
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }

        function downvoteComment(commentId) {
            $.ajax({
                url: '<?= base_url("question/downvote_comment/"); ?>' + commentId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    handleVoteUpdate(commentId, response);
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    </script>
</body>

</html>