<?php
use app\classes\comments;
use app\classes\posts;

// Display Journal Entry collection on home page
$app->get('/', function($request, $response, $args) {
    // Get all Journal Entries from database 
    $posts = new Posts($this->db);
    $results = $posts->getAllEntries();
     // Assign args a key and set it as results
  $args['posts'] = $results;
  // Render
  return $this->view->render($response, 'home.twig', $args);
});
// View Edited Journal Entry
$app->get('/edit/{id}', function($request, $response, $args) {
  $post = new Posts($this->db);
  $results = $post->getEntry($args['id']);
  // Store results:
  $args['post'] = $results;
  // Display the entry
  return $this->view->render($response, 'edit.twig', $args);
});
// Edit a Journal Entry
$app->post('/edit/{id}', function($request, $response, $args) {
    // Ready edited Journal Entry
    $args = array_merge($args, $request->getParsedBody());
  
    if (!empty($args['title']) && !empty($args['body'])) {
        // Change Journal Entry in database 
        $post = new Posts($this->db);
        $results = $post->editEntry($args['id'], $args['title'], $args['date'], $args['body']);
    }
  return $this->response->withStatus(302)->withHeader('Location', '/detail/post/'. $args['id'] );
});
// Review a single Journal Entry
$app->get('/detail/post/{id}', function($request, $response, $args) {
     // Get Journal Entry from database 
    $post = new Posts($this->db);
    $results = $post->getEntry($args['id']);
    
    // Assign args a key and set it as results
    $args['post'] = $results;
    
    // Gather Comments
    $comment = new Comments($this->db);
    $postComment = $comment->getAllComments($args['id']);
    
    // Assign args a key and set it as Entry's Comment
    if (!empty($postComment)) {
      $args['comments'] = $postComment;
    }
    // Display Journal Entry with Comments for user
  return $this->view->render($response, 'detail.twig', $args);
});
// Add a Comment to a Journal Entry
$app->post('/detail/post/{id}', function($request, $response, $args) {
    // Ready our Comment
    $args = array_merge($args, $request->getParsedBody());
  
    // Add Comment to database
    $comment = new Comments($this->db);
    $addComment = $comment->addComment($args['name'], $args['body'], $args['id']);
  
    // Display Comment on Journal Entry
    return $this->response->withStatus(302)->withHeader('Location', '/detail/post/'. $args['id']);
  });
  
  // Delete a Journal Entry and related Comments
$app->post('/delete/{id}', function($request, $response, $args) {

    $post = new Posts($this->db);
    $deleteEntry = $post->deleteEntry($args['id']);
   
    $comment = new Comments($this->db);
    if (!empty($comment->getAllComments($args['id']))) {
      $deleteComment = $comment->deleteComment($args['id']);
    }
    // Return user to Home
    return $this->response->withStatus(302)->withHeader('Location', '/');
  });
  
  // New Journal Entry Form
  $app->get('/new', function($request, $response) {  
    return $this->view->render($response, 'new.twig');
  });

  // Add New Entry
  $app->post('/new', function($request, $response, $args) {  
    // Ready our Post
    $args = array_merge($args, $request->getParsedBody());
    $args['date'] = date('m-d-Y');

    if (!empty($args['title']) && !empty($args['date']) &&!empty($args['body'])){
      $post = new Posts($this->db);
      $results = $post->addEntry($args['title'], $args['date'], $args['body']);
    }
    // Return user to Home
    return $this->response->withStatus(302)->withHeader('Location', '/');
  });