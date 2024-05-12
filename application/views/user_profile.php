<style>
    .input {
        min-width: 40%;
    }

    .profile_form {
        text-align: center;
    }

    .errorMessage {
        color: red;
    }
</style>

<div class="ui segment">
    <?php echo validation_errors(); ?>

    <?php echo form_open(site_url('/SiteController/createProfile')); ?>
    <div class="profile_form">
        <div class="ui segment ui input focus">
            <input type="text" id="profileName" name="profileName" placeholder="Profile Name"
                   value="<?php echo $profileData[0]->getProfileName(); ?>" required>
        </div>
        <div class="ui segment ui input focus">
            <input type="email" id="emailAddress" name="emailAddress" placeholder="Email (sara@gmail.com)"
                   value="<?php echo $profileData[0]->getUserEmail(); ?>" required>
        </div>
        <div class="ui segment ui input focus">
            <input type="url" id="avatarUrl" name="avatarUrl" placeholder="Image URL (https://i.imgur.com/MI8uxnl.jpg)"
                   value="<?php echo $profileData[0]->getAvatarUrl(); ?>">
        </div>
        <div class="ui segment ui input focus">
            <div class="ui fluid multiple search selection dropdown">
                <input type="hidden" name="genres" id="genres" value="<?php echo $genreData[0]->getLikedGenres(); ?>"
                       required>
                <i class="dropdown icon"></i>
                <div class="default text">Select Genres</div>
                <div class="menu">
                    <div class="item" data-value="fiction">Fiction</div>
                    <div class="item" data-value="non-fiction">Non-fiction</div>
                    <div class="item" data-value="dubstep">Mystery</div>
                    <div class="item" data-value="techno">Thriller</div>
                    <div class="item" data-value="country">Horror</div>
                    <div class="item" data-value="electro">Romance</div>
                    <div class="item" data-value="pop">Fantasy</div>
                    <div class="item" data-value="scifi">Science Fiction</div>

                </div>
            </div>
        </div>
        <br><br>
        <button class="ui grey button" type="submit">Submit</button>
        <br><br>
    </div>
    <?php echo form_close(); ?>

    <div class="profile_form">
        <a style="text-align: center" href="<?php echo site_url('/SiteController/deleteProfile'); ?>">
            <button class="ui red button">Delete Account</button>
        </a>
    </div>


</div>

<script language="javascript">
    document.title = "Profile";
</script>


<script>
    document.title = "Edit Profile";

    $('.ui.dropdown').dropdown();

    $('.ui .input').on('click', function () {
        $('.ui .input').removeClass('active');
        $(this).addClass('active');
    });
</script>
