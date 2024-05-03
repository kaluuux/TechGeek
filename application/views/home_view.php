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
    <div class="home-view-top">
    <h1>Recent Questions</h1>
    <!-- Button to open modal -->
    <div class="add-question">
        <?php if ($logged_in): ?>
            <button id="openModal">Ask a Question</button>
            <!-- Modal -->
            <div id="questionModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <form action="<?php echo base_url('question/add'); ?>" method="post">
                        <label for="title">Question Title:</label><br>
                        <input type="text" id="title" name="title" required><br>
                        <label for="description">Question Description:</label><br>
                        <textarea id="description" name="description" rows="4" required></textarea><br>
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
        <select id="sortQuestions" onchange="sortQuestions()">
            <option value="recent">Most Recent</option>
            <option value="most_upvotes">Most Upvotes</option>
            <option value="most_downvotes">Most Downvotes</option>
            <option value="most_views">Most Views</option>
        </select>
    </div>
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
            <div class="question-card-views">
            <p>Views: <?php echo $question->view_count; ?></p>
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
    </script>

</body>
</html>
