<?php
namespace helpers;

require_once __DIR__ . '/../../vendor/autoload.php';

use config\bootstrap;
use Faker\Factory;
use models\Article;

class seeder
{
    /**
     * @var Article
     */
    private $model;

    /**
     * seeder constructor.
     */
    public function __construct()
    {
        $bootstrap = new bootstrap();
        $this->model = new Article($bootstrap);
    }

    /**
     * Inserting {COUNT_SEEDS} entries to articles table
     */
    public function seed()
    {
        $faker = Factory::create();

        for ($i = 0, $iMax = getenv('COUNT_SEEDS'); $i < $iMax; $i++) {

            $description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
            Ut enim ad minim veniam, id*1{$i} id*2{$i} id*3{$i} id*4{$i} quis nostrud exercitation ullamco laboris 
            nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate 
            velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, 
            sunt in culpa qui officia deserunt mollit anim id est laborum.";

            $short_description = $faker->text(200);

            $this->model->insert([
                'title'             => $faker->text(10),
                'description'       => $description,
                'category'          => $faker->text(10),
                'author'            => $faker->name,
                'short_description' => $short_description,
            ]);
        }
    }
}

$seed = new seeder();
$seed->seed();