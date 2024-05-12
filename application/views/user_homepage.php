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

    .maincontent {
        min-width: 800px;
    }

    .userTitle {
        color: inherit;
    }
</style>

<div class="ui raised very padded text container segment center aligned"  style="margin-left: 5%; margin-right: 5%;">
    <div class="ui people shape">
        <div class="sides">
            <div class="active side">
                <div class="ui card">
                    <div class="image">
                        <img src="<?php echo $profileData[0]->getAvatarUrl(); ?>">
                    </div>
                    <div class="content">
                        <?php if ($profileData[0]->getProfileName() !== NULL) { ?>
                            <div class="header"><?php echo $profileData[0]->getProfileName(); ?></div>
                            <div class="meta">
                                <a><?php echo '@' . $profileData[0]->getUserName(); ?></a>
                            </div>
                        <?php } else { ?>
                            <div class="header"><?php echo '@' . $profileData[0]->getUsername(); ?></div>
                            <div class="meta">
                                <a><?php echo $profileData[0]->getUserEmail(); ?></a>
                            </div>
                        <?php } ?>
                        <div class="description">
                            <?php if ($genreData[0]->getTransformedLikedGenres() !== '') { ?>
                                Genres: <?php echo $genreData[0]->getTransformedLikedGenres(); ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="extra content">
                        <a>
                            <a href="<?php echo site_url('/SiteController/profile'); ?>">
                                <button class="ui blue button">Edit Profile</button>
                            </a>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ui raised very padded text container segment" style="margin-left: 5%; margin-right: 5%;">
    <?php echo validation_errors(); ?>

    <?php echo form_open(site_url('/SiteController/createHomePost')); ?>
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

<?php if (!empty($publishedPosts)) { ?>
        <div class="ui raised very padded text container segment">
        <h2>Published</h2>
        <ul>
        <?php foreach ($publishedPosts as $post) { ?>
                <div class="ui segment posts">
                    <div class="postAvatarImage">
                        <p><img align="top" src="<?php echo $profileData[0]->getAvatarUrl(); ?>">
                            <?php if ($profileData[0]->getProfileName() !== NULL) { ?>
                                <b style="margin-left: 10px;"><?php echo $profileData[0]->getProfileName(); ?></b><?php echo ' @' . $profileData[0]->getUsername(); ?>
                            <?php } else { ?>
                                <b><?php echo '@' . $profileData[0]->getUsername(); ?></b>
                            <?php } ?>
                        <div class="ui text menu" style="margin-top: -75px;">
                            <div class="ui right dropdown item">
                                Options
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    <div class="item"><a href="<?php echo site_url('/SiteController/editPost/' . $post->getPostId()); ?>">Edit
                                            Post</a></div>
                                    <div class="item"><a href="<?php echo site_url('/SiteController/deletePost/' . $post->getPostId()); ?>">Delete
                                            Post</a></div>
                                </div>
                            </div>
                        </div>
                        </p>
                    </div>
                    <br>
                    <h2 class="postTitle" style="margin-left: 65px;"><?php echo $post->title; ?></h2>
                    <p class="postContent" style="margin-left: 65px;"><?php
                                                                        $output = $post->postContent; ?>

                    <p class="postContent" style="margin-left: 65px;"><?php echo $post->postContent; ?></p>
                    <?php $this->load->view('like_button', ['postId' => $post->getPostId(), 'isLiked' => $isLiked]); ?>
                    <div>
                        <form method="post" action="<?php echo site_url('SiteController/reactToPostHomePage'); ?>">
                            <input type="hidden" name="postId" value="<?php echo $post->postId; ?>">
                            <button type="submit" name="reaction" value="happy">😊</button>
                            <span><?php echo $post->happy_count; ?></span>
                            <button type="submit" name="reaction" value="surprised">😲</button>
                            <span><?php echo $post->surprised_count; ?></span>
                            <button type="submit" name="reaction" value="sad">😢</button>
                            <span><?php echo $post->sad_count; ?></span>
                            <button type="submit" name="reaction" value="angry">😡</button>
                            <span><?php echo $post->angry_count; ?></span>
                            <button type="submit" name="reaction" value="laughing">😆</button>
                            <span><?php echo $post->laughing_count; ?></span>
                            <button type="submit" name="reaction" value="fire">🔥</button>
                            <span><?php echo $post->fire_count; ?></span>
                        </form>
                    </div>
                    <?php if (!empty($post->image)) : ?>
                        <img src="<?php echo $post->image; ?>" alt="Post Image" style="max-width: 100%; height: auto; max-height: 10rem; display: block; margin: 0 auto;">
                    <?php endif; ?>
                    <a href="<?php echo site_url('/SiteController/viewPost/' . $post->postId); ?>">View Post</a>

                </div>

            <?php } ?>
        </ul>
    </div>
<?php } ?>

<?php if (!empty($draftPosts)) { ?>
        <div class="ui raised very padded text container segment">
        <h2>Drafts</h2>
        <ul>
        <?php foreach ($draftPosts as $post) { ?>
                <div class="ui segment posts">
                    <div class="postAvatarImage">
                        <p><img align="top" src="<?php echo $profileData[0]->getAvatarUrl(); ?>">
                            <?php if ($profileData[0]->getProfileName() !== NULL) { ?>
                                <b style="margin-left: 10px;"><?php echo $profileData[0]->getProfileName(); ?></b><?php echo ' @' . $profileData[0]->getUsername(); ?>
                            <?php } else { ?>
                                <b><?php echo '@' . $profileData[0]->getUsername(); ?></b>
                            <?php } ?>
                        <div class="ui text menu" style="margin-top: -75px;">
                            <div class="ui right dropdown item">
                                Options
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    <div class="item"><a href="<?php echo site_url('/SiteController/editPost/' . $post->getPostId()); ?>">Edit
                                            Post</a></div>
                                    <div class="item"><a href="<?php echo site_url('/SiteController/deletePost/' . $post->getPostId()); ?>">Delete
                                            Post</a></div>
                                </div>
                            </div>
                        </div>
                        </p>
                    </div>
                    <br>
                    <h2 class="postTitle" style="margin-left: 65px;"><?php echo $post->title; ?></h2>
                    <p class="postContent" style="margin-left: 65px;"><?php
                                                                        $output = $post->postContent; ?>

                    <p class="postContent" style="margin-left: 65px;"><?php echo $post->postContent; ?></p>
                    <?php $this->load->view('like_button', ['postId' => $post->getPostId(), 'isLiked' => $isLiked]); ?>
                    <div>
                        <form method="post" action="<?php echo site_url('SiteController/reactToPostHomePage'); ?>">
                            <input type="hidden" name="postId" value="<?php echo $post->postId; ?>">
                            <button type="submit" name="reaction" value="happy">😊</button>
                            <span><?php echo $post->happy_count; ?></span>
                            <button type="submit" name="reaction" value="surprised">😲</button>
                            <span><?php echo $post->surprised_count; ?></span>
                            <button type="submit" name="reaction" value="sad">😢</button>
                            <span><?php echo $post->sad_count; ?></span>
                            <button type="submit" name="reaction" value="angry">😡</button>
                            <span><?php echo $post->angry_count; ?></span>
                            <button type="submit" name="reaction" value="laughing">😆</button>
                            <span><?php echo $post->laughing_count; ?></span>
                            <button type="submit" name="reaction" value="fire">🔥</button>
                            <span><?php echo $post->fire_count; ?></span>
                        </form>
                    </div>
                    <?php if (!empty($post->image)) : ?>
                        <img src="<?php echo $post->image; ?>" alt="Post Image" style="max-width: 100%; height: auto; max-height: 10rem; display: block; margin: 0 auto;">
                    <?php endif; ?>
                    <a href="<?php echo site_url('/SiteController/viewPost/' . $post->postId); ?>">View Post</a>

                </div>

            <?php } ?>
        </ul>
    </div>
<?php } ?>

<script>
    document.title = "Home";
    $('.ui.dropdown')
        .dropdown();
</script>