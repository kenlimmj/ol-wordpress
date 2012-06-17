  <div id="comments">
    <?php
            $req = get_option('require_name_email'); // Checks if fields are required.
            if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
                    die ( 'Please do not load this page directly. Thanks!' );
            if ( ! empty($post->post_password) ) :
                    if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) :
    ?>

    <div class="nopassword">
      <?php _e('This post is password protected. Enter the password to view any comments.', 'shape') ?>
    </div>
  </div><!-- .comments -->
  <?php
                  return;
          endif;
  endif;
  ?><?php if ( have_comments() ) : ?><?php /* numbers of pings and comments */
  $ping_count = $comment_count = 0;
  foreach ( $comments as $comment )
          get_comment_type() == "comment" ? ++$comment_count : ++$ping_count;
  ?><?php if ( ! empty($comments_by_type['comment']) ) : ?>

  <div id="comments-list" class="comments">
    <h3><?php printf($comment_count > 1 ? __('<span>%d</span> Comments', 'shape') : __('<span>One</span> Comment', 'shape'), $comment_count) ?></h3>

    <ol>
      <?php wp_list_comments('type=comment&callback=custom_comments'); ?>
    </ol><?php $total_pages = get_comment_pages_count(); if ( $total_pages > 1 ) : ?>

    <div id="comments-nav-below" class="comments-navigation">
      <div class="paginated-comments-links">
        <?php paginate_comments_links(); ?>
      </div>
    </div><!-- #comments-nav-below -->
    <?php endif; ?>
  </div><!-- #comments-list .comments -->
  <?php endif; /* if ( $comment_count ) */ ?><?php if ( ! empty($comments_by_type['pings']) ) : ?>

  <div id="trackbacks-list" class="comments">
    <h3><?php printf($ping_count > 1 ? __('<span>%d</span> Trackbacks', 'shape') : __('<span>One</span> Trackback', 'shape'), $ping_count) ?></h3>

    <ol>
      <?php wp_list_comments('type=pings&callback=custom_pings'); ?>
    </ol>
  </div><!-- #trackbacks-list .comments -->
  <?php endif /* if ( $ping_count ) */ ?><?php endif /* if ( $comments ) */ ?><?php if ( 'open' == $post->comment_status ) : ?>

  <div id="respond">
    <h3><?php comment_form_title( __('Post a Comment', 'shape'), __('Post a Reply to %s', 'shape') ); ?></h3>

    <div id="cancel-comment-reply">
      <?php cancel_comment_reply_link() ?>
    </div><?php if ( get_option('comment_registration') && !$user_ID ) : ?>

    <p id="login-req"><?php printf(__('You must be <a href="%s" title="Log in">logged in</a> to post a comment.', 'shape'),
                                            get_option('siteurl') . '/wp-login.php?redirect_to=' . get_permalink() ) ?></p><?php else : ?>

    <div class="formcontainer">
      <form id="commentform" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">
        <?php if ( $user_ID ) : ?>

        <p id="login"><?php printf(__('<span class="loggedin">Logged in as <a href="%1$s" title="Logged in as %2$s">%2$s</a>.</span> <span class="logout"><a href="%3$s" title="Log out of this account">Log out?</a></span>', 'shape'),
                                                                        get_option('siteurl') . '/wp-admin/profile.php',
                                                                        wp_specialchars($user_identity, true),
                                                                        wp_logout_url(get_permalink()) ) ?></p><?php else : ?>

        <p id="comment-notes">
        <?php _e('Your email is <em>never</em> published nor shared.', 'shape') ?>
        <?php if ($req) _e('Required fields are marked <span class="required">*</span>', 'shape') ?></p>

        <fieldset>
          <div id="form-section-author" class="form-section">
            <div class="clearfix">
              <div class="form-label">
                <label for="author"><?php _e('Name', 'shape') ?></label>
                <?php if ($req) _e('<span class="required">*</span>', 'shape') ?>
              </div>

              <div class="input">
                <input id="author" class="xlarge" name="author" type="text" value="<?php echo $comment_author ?>" size="30" maxlength="20" tabindex="3">
              </div>
            </div>
          </div><!-- #form-section-author .form-section -->

          <div id="form-section-email" class="form-section">
            <div class="clearfix">
              <div class="form-label">
                <label for="email"><?php _e('Email', 'shape') ?></label>
                <?php if ($req) _e('<span class="required">*</span>', 'shape') ?>
              </div>

              <div class="form-input">
                <input id="email" name="email" type="text" class="xlarge" value="<?php echo $comment_author_email ?>" size="30" maxlength="50" tabindex="4">
              </div>
            </div>
          </div><!-- #form-section-email .form-section -->

          <div id="form-section-url" class="form-section">
            <div class="clearfix">
              <div class="form-label">
                <label for="url"><?php _e('Website', 'shape') ?></label>
              </div>

              <div class="form-input">
                <input id="url" name="url" type="text" class="xlarge" value="<?php echo $comment_author_url ?>" size="30" maxlength="50" tabindex="5">
              </div>
            </div>
          </div><!-- #form-section-url .form-section -->
          <?php endif /* if ( $user_ID ) */ ?>

          <div id="form-section-comment" class="form-section">
            <div class="clearfix">
              <div class="form-label">
                <label for="comment"><?php _e('Comment', 'shape') ?></label>
              </div>

              <div class="input">
                <div class="form-textarea">
                  <textarea id="comment" name="comment" cols="45" rows="8" tabindex="6"></textarea>
                </div>
              </div>
            </div><!-- #form-section-comment .form-section -->
            <?php do_action('comment_form', $post->ID); ?>

            <div class="actions">
              <div class="form-submit">
                <input id="submit" name="submit" type="submit" class="btn primary" value="<?php _e('Post Comment', 'shape') ?>" tabindex="7">
                <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>">
              </div>
            </div>
          </div>
        </fieldset><?php comment_id_fields(); ?>
      </form><!-- #commentform-->
    </div><!-- .formcontainer -->
    <?php endif /* if ( get_option('comment_registration') && !$user_ID ) */ ?>
  </div><!-- #respond -->
  <?php endif /* if ( 'open' == $post->comment_status ) */ ?>
  <!-- #comments -->