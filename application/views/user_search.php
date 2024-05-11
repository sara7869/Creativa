<style>

    .ui.fluid.inner.search.selection.dropdown {
        max-width: 60%;
    }

    .move {
        padding-left: 30%;
    }

    .errorMessage {
        color: red;
    }

</style>

<div class="ui raised very padded text container segment center aligned">
    <form class="search_users" action="<?php echo site_url('/SiteController/search'); ?>" method="get">
        <input type="text" name="query" placeholder="Search for posts or users...">

        <br><br>
        <button class="ui grey button" type="submit">Search</button>
    </form>
</div>

<!-- Display Users -->
<div class="ui raised very padded text container segment">
    <div class="ui middle aligned divided list">
        <h2>Users</h2>
        <?php foreach ($users as $user) {?>
            <div class="user">
                <h3><a href="<?php echo site_url('/SiteController/viewUserProfile/') ?>" </a></h3>
                <p><?php echo $user->username;?></p>
                <!-- Like button -->
                <!-- Comment button -->
            </div>
        <?php }?>
        <?php if (count($users) == 0) {?>
            <p class="errorMessage">No users found.</p>
        <?php }?>
    </div>
</div>

<!-- Display Posts -->
<div class="ui raised very padded text container segment">
    <div class="ui middle aligned divided list">
        <h2>Posts</h2>
        <?php foreach ($posts as $post) {?>
            <div class="post">
                <h3><a href="<?php echo site_url('/SiteController/viewPost/'.$post['postId']);?>"><?php echo $post['title'];?></a></h3>
                <!-- <img src="<?php echo $post['image']; ?>" alt="Post Image" style="max-width: 100%; height: auto; max-height: 10rem; display: block; margin: 0 auto;"> -->
                <p><?php echo $post['postContent'];?></p>
                <a href="<?php echo site_url('/SiteController/likePost/'.$post['postId']);?>">Like</a></a>
                <a href="<?php echo site_url('/SiteController/add_comment/'.$post['postId']);?>">Comment</a></a>
            </div>
        <?php }?>
        <?php if (count($posts) == 0) {?>
            <p class="errorMessage">No posts found.</p>
        <?php }?>
    </div>
</div>

<script>
    document.title = "Search";
    $('.ui.dropdown').dropdown();
</script>
