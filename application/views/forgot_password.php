<style type="text/css">
    body {
        background-image: url('https://images.unsplash.com/photo-1629375286699-8faeaa9aab59?q=80&w=1776&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
    }

    body>.grid {
        height: 100%;
    }

    .image {
        margin-top: -100px;
    }

    .column {
        max-width: 450px;
    }

    .errorMessage {
        color: red;
    }
</style>

<div class="ui middle aligned center aligned grid">
    <div class="column">
        <h2 class="ui teal image header">
            <div class="content">
                Forgot Password
            </div>
        </h2>


        <?php echo validation_errors();

        if ($this->session->has_userdata('errorMsg')) {
            echo '<br><div class="errorMessage">' . $this->session->errorMsg . '</div><br>';
            $this->session->unset_userdata('errorMsg');
        }
        ?>

        <?php echo form_open(site_url('/UserController/validateSecretQuestionAnswer')); ?>
        <div class="ui large form">
            <div class="ui stacked segment">
                <div class="field">
                    <div class="ui left icon input">
                        <i class="user icon"></i>
                        <input type="text" name="username" id="username" placeholder="Username" required>
                    </div>
                </div>
                <div class="field">
                    <select name="secretQuestionId" required>
                        <option value="">Select Secret Question</option>
                        <?php foreach ($secretQuestions as $question) { ?>
                            <option value="<?php echo $question->question_id; ?>"><?php echo $question->question_text; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="field">
                    <input type="text" name="secretQuestionAnswer" placeholder="Answer to Secret Question" required>
                </div>
                <button class="ui fluid large teal submit button" type="submit" value="Submit">
                    Submit
                </button>
            </div>
        </div>
        <?php echo form_close(); ?>

        <div class="ui message">
            New to us? <a href="<?php echo site_url('/UserController/registration'); ?>">Sign Up</a>
        </div>
    </div>
</div>

<script>
    document.title = "Forgot Password";
</script>