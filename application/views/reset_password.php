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
                Reset Password
            </div>
        </h2>


        <?php echo validation_errors();

        if ($this->session->has_userdata('errorMsg')) {
            echo '<br><div class="errorMessage">' . $this->session->errorMsg . '</div><br>';
            $this->session->unset_userdata('errorMsg');
        }
        ?>

        <?php echo form_open(site_url('/UserController/registerUser')); ?>
        <div class="ui large form">
            <div class="ui stacked segment">
                <div class="field">
                    <div class="ui left icon input">
                        <i class="lock icon"></i>
                        <input type="password" id="password" name="password" placeholder="New Password" required>
                    </div>
                </div>
                <div class="field">
                    <div class="ui left icon input">
                        <i class="lock icon"></i>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                    </div>
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
    document.title = "Reset Password";
</script>