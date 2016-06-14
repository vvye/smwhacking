<?php

	require_once __DIR__ . '/../functions/database.php';


	class User
	{
		static $powerlevelDescriptions = [
			0 => 'Normaler Nutzer',
			1 => 'Moderator',
			2 => 'Administrator'
		];

		static $database;
		private $id;
		private $name;
		private $powerlevel;
		private $title;
		private $signature;
		private $email;
		private $registrationTime;
		private $lastLoginTime;
		private $bio;
		private $website;
		private $banned;
		private $threadsPerPage = 50;
		private $postsPerPage = 20;


		public function __construct($id)
		{
			self::$database = getDatabase();

			$this->id = $id;

			$users = self::$database->select('users', [
				'id',
				'name',
				'title',
				'powerlevel',
				'signature',
				'registration_time',
				'last_login_time',
				'bio',
				'website',
				'email',
				'banned'
			], [
				'id'    => $id,
				'LIMIT' => 1
			]);

			if (count($users) !== 1)
			{
				throw new Exception("Diesen Nutzer gibt es nicht.");
			}

			$user = $users[0];
			$this->id = $user['id'];
			$this->name = $user['name'];
			$this->powerlevel = $user['powerlevel'];
			$this->title = $user['title'];
			$this->signature = $user['signature'];
			$this->email = $user['email'];
			$this->registrationTime = $user['registration_time'];
			$this->lastLoginTime = $user['last_login_time'];
			$this->bio = $user['bio'];
			$this->website = $user['website'];
			$this->banned = $user['banned'];
		}


		/**
		 * @return mixed
		 */
		public function getId()
		{
			return $this->id;
		}


		/**
		 * @return mixed
		 */
		public function getName()
		{
			return $this->name;
		}


		/**
		 * @return mixed
		 */
		public function getPowerlevel()
		{
			return self::$powerlevelDescriptions[$this->powerlevel];
		}


		/**
		 * @return mixed
		 */
		public function getTitle()
		{
			return $this->title;
		}


		/**
		 * @return mixed
		 */
		public function getSignature()
		{
			return $this->signature;
		}


		/**
		 * @return mixed
		 */
		public function getRegistrationTime()
		{
			return $this->registrationTime;
		}


		/**
		 * @return mixed
		 */
		public function getLastLoginTime()
		{
			return $this->lastLoginTime;
		}


		/**
		 * @return mixed
		 */
		public function getBio()
		{
			return $this->bio;
		}


		/**
		 * @return mixed
		 */
		public function getWebsite()
		{
			return $this->website;
		}


		/**
		 * @return mixed
		 */
		public function getBanned()
		{
			return $this->banned;
		}


		public function getPosts($page)
		{
			$offset = ($page - 1) * $this->postsPerPage;

			$posts = self::$database->select('posts', [
				'[>]threads' => ['thread' => 'id'],
			], [
				'posts.id',
				'posts.post_time',
				'posts.content',
				'threads.id(thread_id)',
				'threads.name(thread_name)'
			], [
				'author' => $this->id,
				'ORDER'  => 'post_time ASC',
				'LIMIT'  => [$offset, $this->postsPerPage]
			]);

			return $posts;
		}


		public function getNumPosts()
		{
			return self::$database->count('posts', [
				'author' => $this->id
			]);
		}


		public function getPostIndex($postId)
		{
			return self::$database->count('posts', [
				'AND' => [
					'author' => $this->id,
					'id[<=]' => $postId
				]
			]);
		}


		// TODO return only the URL
		public function getAvatarHtml()
		{
			return '<img class="avatar" src="img/avatars/' . $this->id . '.png" alt="Avatar" />';
		}


		public function getRank()
		{
			$ranks = self::$database->select('ranks', '*', [
				'min_posts[<=]' => $this->getNumPosts(),
				'ORDER'         => 'min_posts DESC',
				'LIMIT'         => '1',
			]);

			return $ranks[0];
		}


		// TODO refactor
		public function getRankHtml()
		{
			$rank = $this->getRank();
			// TODO check if file exists?
			$imageHtml = $rank['has_image'] ? '<img src="img/ranks/' . $rank['id'] . '.png" alt="' . $rank['name'] . '" />' : '';

			return '<p>' . $rank['name'] . '</p>' . $imageHtml;
		}


		// TODO refactor
		public function getProfileRankHtml()
		{
			$rank = $this->getRank();

			// TODO check if file exists?
			$imageHtml = $rank['has_image'] ? '<img src="img/ranks/' . $rank['id'] . '.png" alt="' . $rank['name'] . '" />' : '';

			return $imageHtml . ' ' . $rank['name'];
		}


		public function getLastPost()
		{
			$posts = self::$database->select('posts', [
				'[>]threads' => ['thread' => 'id']
			], [
				'posts.id',
				'posts.post_time',
				'threads.id(thread_id)',
				'threads.name(thread_name)'
			], [
				'author' => $this->id,
				'ORDER'  => 'post_time DESC',
				'LIMIT'  => 1
			]);

			if (count($posts) !== 1)
			{
				return null;
			}

			return $posts[0];
		}


		/**
		 * @return mixed
		 */
		public function getEmail()
		{
			return $this->email;
		}

	}