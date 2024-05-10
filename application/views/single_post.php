<style>
    .submit-button {
        text-align: center;
    }

    .posts {
        min-height: 100px;
        font-size: 14px;
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
</style>

<div class="ui vertically divided grid">
    <div class="three column row">
        <div class="column"></div>
        <div class="column">
            <div class="ui container segment">
                <?php echo validation_errors(); ?>

                <div class="post-content">
                    <?php echo $post['postContent'];?>
                </div>

                <div class="likes" style="margin-top: 1.5rem;">
                    <p><strong>Likes: <?php echo count($likes);?></strong></p>
                </div>

                <div class="comments" style="margin-top: 1.5rem;">
                    <h3>Comments</h3>
                    <?php foreach ($comments as $comment) :?>
                        <div class="comment">
                            <p><strong>User <?php echo $comment['user_id'];?></strong> said:</p>
                            <p><?php echo $comment['comment'];?></p>
                        </div>
                    <?php endforeach;?>
                </div>

                <!-- Your existing form for adding a comment -->
                <form class="ui form" action="<?php echo site_url('SiteController/add_comment');?>" method="post">
                    <input type="hidden" name="postId" value="<?php echo $postId;?>">
                    <textarea name="comment"></textarea>
                    <button type="submit" class="grey button">Add Comment</button>
                </form>
            </div>

        </div>
        <div class="column"></div>
    </div>
</div>

<script>
    document.title = "View Post";
</script>