<?php
/**
 * MIT License
 *
 * Copyright (c) 2019 Filli Group (Einzelunternehmen)
 * Copyright (c) 2019 Filli IT (Einzelunternehmen)
 * Copyright (c) 2019 Ursin Filli
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

namespace App\Action;

use App\Models\Author;
use App\Models\Post;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

final class HomeAction
{
    private $view;
    private $logger;

    public function __construct(Twig $view, LoggerInterface $logger)
    {
        $this->view = $view;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");

        $posts_db = Post::all();
        $posts = [];
        foreach ($posts_db as $post) {
            $author = Author::find($post->authorId);
            array_push($posts, [
                'post' => $post->id,
                'title' => $post->title,
                'description' => $post->description,
                'date_create' => date('F d, Y', strtotime($post->created_at)),
                'author_name' => $author->name,
                'author_id' => $author->id,
            ]);
        }

        $this->view->render($response, 'pages/home.twig', array(
            'title' => 'Home',
            'page_title' => 'Clean Blog',
            'page_sub_title' => 'A Blog Theme by Start Bootstrap',
            'bg_img' => 'https://cdn.statically.io/img/i.imgur.com/LcWoFNZ.jpg',
            'posts' => $posts,
        ));
        return $response;
    }
}
