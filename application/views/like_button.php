<!-- In views/like_button.php -->

<div class="like-button">
    <form method="post" action="<?php echo site_url('SiteController/likePost/'.$postId);?>">
        <input type="hidden" name="postId" value="<?php echo $postId;?>">
        <button type="submit" class="btn btn-primary">
            <?php echo $isLiked? 'Unlike' : 'Like';?>
        </button>
    </form>
</div>