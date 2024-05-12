<style>
    .submit-button {
        text-align: center;
    }

    .posts {
        min-height: 150px;
        max-height: 400px;
        font-size: 14px;
        overflow: auto;
    }

    .postContent p {
        margin-left: 10px;
    }

    .postContent img {
        max-height: 290px;
        max-width: 80%;
    }

    .shape {
        text-align: center;
    }

    .postAvatarImage img {
        border-radius: 50%;
        width: 50px;
    }

    .postAvatarImage p {
        color: dimgrey;
    }

    ul {
        margin: 0;
        padding: 0;
    }

    .errorMessage {
        color: red;
    }

    .userTitle {
        color: inherit;
    }
</style>

<div class="ui raised very padded text container segment" style="margin-left: 5%; margin-right: 5%;">
    <?php echo validation_errors(); ?>

    <?php echo form_open(site_url('/SiteController/createTimelinePost')); ?>

    <div class="ui form">
        <div class="field">
            <label>Title</label>
            <input type="text" name="title" placeholder="Title" required>
        </div>
        <div class="field">
            <label>Image URL (optional)</label>
            <input type="text" name="image" placeholder="Image URL (optional)">
        </div>
        <div class="field">
            <label>Post Content</label>
            <textarea spellcheck="false" id="postContent" name="postContent"></textarea>
        </div>
        <div class="field">
            <label>Categories</label>
            <input type="text" name="category" placeholder="Categories (comma-separated)">
        </div>
        <div class="field">
            <label>Tags</label>
            <input type="text" name="tags" placeholder="Tags (comma-separated)">
        </div>
        <div class="field">
            <label>Status</label>
            <select name="status">
                <option value="Draft">Draft</option>
                <option value="Published">Published</option>
            </select>
        </div>
        <div class="submit-button">
            <button class="ui grey button" type="submit">Post</button>
        </div>

    </div>

    <?php echo form_close(); ?>
</div>

<?php if ($timelinePosts !== null) { ?>
    <div class="ui raised very padded text container segment">
        <ul>
            <?php foreach ($timelinePosts as $post) { ?>

                <div class="ui raised very padded text container segment posts">
                    <div class="postAvatarImage">
                        <p>
                            <a class="userTitle" href="<?php echo site_url('/SiteController/viewUserProfile/' . $post->userId) ?>">
                                <img align="top" src="<?php echo $post->avatarUrl; ?>">
                                <?php if ($post->profileName !== NULL) { ?>
                                    <b style="margin-left: 10px;"><?php echo $post->profileName; ?></b><?php echo ' @' . $post->username; ?>
                                <?php } else { ?>
                                    <b><?php echo '@' . $post->username; ?></b>
                                <?php } ?>
                            </a>
                            <?php if ($this->session->userdata('userId') == $post->userId) { ?>
                        <div class="ui text menu" style="margin-top: -75px;">
                            <div class="ui right dropdown item">
                                Options
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    <div class="item"><a href="<?php echo site_url('/SiteController/editPost/' . $post->postId); ?>">Edit Post</a></div>
                                    <div class="item"><a href="<?php echo site_url('/SiteController/deletePost/' . $post->postId); ?>">Delete Post</a></div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                        </p>
                    </div>
                    <br>
                    <h2 class="postTitle" style="margin-left: 65px;"><?php echo $post->title; ?></h2>
                    <p class="postContent" style="margin-left: 65px;"><?php
                                                                        $output = $post->postContent;

                                                                        $regex_images = '~https?://\S+?(?:png|gif|jpe?g)~';
                                                                        $regex_links = '~(?<!src=\')https?://\S+\b~';

                                                                        $output = preg_replace($regex_images, "<br> <img src='\\0'> <br>", $output);
                                                                        $output = preg_replace($regex_links, "<a href='\\0' target=\"_blank\">\\0</a>", $output);

                                                                        echo $output;
                                                                        ?></p>
                    <?php if (!empty($post->image)) : ?>
                        <img src="<?php echo $post->image; ?>" alt="Post Image" style="max-width: 100%; height: auto; max-height: 10rem; display: block; margin: 0 auto;">
                    <?php endif; ?>
                    <a href="<?php echo site_url('/SiteController/viewPost/' . $post->postId); ?>">View Post</a>

                    <div>
                        <form method="post" action="<?php echo site_url('SiteController/likePost'); ?>">
                            <div class="like-button">
                                <input type="hidden" name="postId" value="<?php echo $post->postId; ?>">
                                <button type="submit" class="btn ui grey button">Like</button>
                            </div>
                        </form>
                    </div>

                    <div class="like-count">
                        Likes: <?php echo $post->like_count; ?>
                    </div>
                </div>

            <?php } ?>
        </ul>
    </div>
<?php } ?>

<script>
    document.title = "Timeline";
    $('.ui.dropdown')
        .dropdown();
</script>