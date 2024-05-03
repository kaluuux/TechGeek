<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/voting.css'); ?>">
    <?php $this->load->view('header', ['title' => 'Home Page']); ?>
    <title>Question Details</title>
</head>
<body>
    <h1>Question Details</h1>
    <p>Asked by: <?php echo $question->username; ?></p>
    <h2><?php echo $question->title; ?></h2>
    <p><?php echo $question->description; ?></p>
    <p>Views: <?php echo $question->view_count; ?></p>

    <span class="upvote-group"><a href="#" class="upvote" onclick="upvoteQuestion(<?php echo $question->id; ?>); return false;"><i class="fas fa-arrow-up">Up-Vote</i></a><span id="upvotes_<?php echo $question->id; ?>"><?php echo $question->upvotes; ?></span></span>
    <span class="downvote-group"><a href="#" class="downvote" onclick="downvoteQuestion(<?php echo $question->id; ?>); return false;"><i class="fas fa-arrow-down">Down-Vote</i></a><span id="downvotes_<?php echo $question->id; ?>"><?php echo $question->downvotes; ?></span></span>
         
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function upvoteQuestion(questionId) {
        $.ajax({
            url: '<?php echo base_url('question/upvote/'); ?>' + questionId,
            type: 'GET',
            dataType: 'json', // Specify JSON data type
            success: function(response) {
                // Update the upvote count on the page
                $('#upvotes_' + questionId).text(response.upvotes);
                // Update the downvote count on the page
                $('#downvotes_' + questionId).text(response.downvotes);
                console.log('Upvote successful');
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
        });
    }

    function downvoteQuestion(questionId) {
        $.ajax({
            url: '<?php echo base_url('question/downvote/'); ?>' + questionId,
            type: 'GET',
            dataType: 'json', // Specify JSON data type
            success: function(response) {
                // Update the upvote count on the page
                $('#upvotes_' + questionId).text(response.upvotes);
                // Update the downvote count on the page
                $('#downvotes_' + questionId).text(response.downvotes);
                console.log('Downvote successful');
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
        });
    }
</script>
</body>
</html>
