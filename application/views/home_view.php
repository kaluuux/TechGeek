<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/home.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/voting.css'); ?>">
    <title>Home Page</title>
    <!-- <a href="<?php echo base_url('auth/logout'); ?>">Logout</a> -->
    <?php $this->load->view('header', ['title' => 'Home Page']); ?>
</head>
<body>
    <h1>Recent Questions</h1>
    <div>
    <?php if ($logged_in): ?>
        <?php foreach($questions as $question): ?>
        <div class="question-card">
            <div class="question-card-title"><a href="<?php echo base_url('question/details/' . $question->id); ?>"><?php echo $question->title; ?></a>
            </div>
            <div class="question-card-vote">
                <span class="upvote-group"><a href="#" class="upvote" onclick="upvoteQuestion(<?php echo $question->id; ?>); return false;"><i class="fas fa-arrow-up">Up-Vote</i></a><span id="upvotes_<?php echo $question->id; ?>"><?php echo $question->upvotes; ?></span></span>
                <span class="downvote-group"><a href="#" class="downvote" onclick="downvoteQuestion(<?php echo $question->id; ?>); return false;"><i class="fas fa-arrow-down">Down-Vote</i></a><span id="downvotes_<?php echo $question->id; ?>"><?php echo $question->downvotes; ?></span></span>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <?php foreach($questions as $question): ?>
        <div class="question-card">
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

    <div class="add-question">

        <?php if ($logged_in): ?>
            <form action="<?php echo base_url('question/add'); ?>" method="post">
            <label for="title">Question Title:</label><br>
            <input type="text" id="title" name="title" required><br>
            <label for="description">Question Description:</label><br>
            <textarea id="description" name="description" rows="4" required></textarea><br>
            <button type="submit">Submit</button>
            </form>
            <!-- Post Question Button -->
        <?php else: ?>
            <!-- Prompt to Log In -->
            <p>Please <a href="<?php echo base_url('login'); ?>">login</a> to post a question.</p>
        <?php endif; ?>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function upvoteQuestion(questionId) {
            $.ajax({
                url: '<?php echo base_url('question/upvote/'); ?>' + questionId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        $('#upvotes_' + questionId).text(response.upvotes);
                        $('#downvotes_' + questionId).text(response.downvotes);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + xhr.responseText); // This will show more detailed error
                }
            });
        }


        function downvoteQuestion(questionId) {
            $.ajax({
                url: '<?php echo base_url('question/downvote/'); ?>' + questionId,
                type: 'POST', // Change to POST for better security
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        $('#upvotes_' + questionId).text(response.upvotes);
                        $('#downvotes_' + questionId).text(response.downvotes);
                        console.log('Downvote successful');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        }
    </script>

</body>
</html>
