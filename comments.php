<?php

// Prevent direct access to this file
if (!defined('ABSPATH')) {
	header('HTTP/1.1 403 Forbidden');
	die('Please do not load this file directly. Thank you.');
}

// Check for password protection
if (post_password_required()) {
	$pass_req = "<p>" . __('This post is password protected. Enter the password to view comments.', 'thesis') . "</p>\n";
	return;
}

$user_data = array(
	'user_ID' => $user_ID,
	'user_identity' => $user_identity,
	'comment_author' => $comment_author,
	'comment_author_email' => $comment_author_email,
	'comment_author_url' => $comment_author_url,
	'req' => $req
);

$thesis_comments = new thesis_comments;
$thesis_comments->output_comments($comments, $user_data);