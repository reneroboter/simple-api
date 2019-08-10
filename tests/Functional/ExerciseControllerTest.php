<?php


namespace App\Tests\Functional;

use App\DataFixtures\ExerciseFixture;
use App\DataFixtures\UserFixture;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class ExerciseControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function setUp(): void
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $metadata = $em->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();
        if (!empty($metadata)) {
            $schemaTool->createSchema($metadata);
        }
        $this->postFixtureSetup();

        $fixtures = [
            UserFixture::class,
            ExerciseFixture::class,
        ];
        $this->loadFixtures($fixtures);
    }

    public function testShowExercise()
    {
        $client = static::createClient();
        $client->request('GET', '/exercises/1');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testShowExercises()
    {
        $client = static::createClient();
        $client->request('GET', '/exercises');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testCreateExercise()
    {
        $server = [];
        $server['CONTENT_TYPE'] = 'application/json';
        $request = '{"exercise": { "name": "Exercise", "reps": "3-4", "date": "2019-08-06"}}';
        $client = static::createClient();
        $client->request('POST', '/exercises', [], [], $server, $request);
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    public function testFailedCreateExercise()
    {
        $server = [];
        $server['CONTENT_TYPE'] = 'application/json';
        $request = '{"exercise": { "name": "", "reps": "3-4`´", "date": "201-08-06"}}';
        $client = static::createClient();
        $client->request('POST', '/exercises', [], [], $server, $request);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
    }

    public function testPutExercise()
    {
        $server = [];
        $server['CONTENT_TYPE'] = 'application/json';
        $request = '{"exercise": { "name": "Exercise updated", "reps": "4-5", "date": "2019-08-06"}}';
        $client = static::createClient();
        $client->request('PUT', '/exercises/1', [], [], $server, $request);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testFailedPutExercise()
    {
        $server = [];
        $server['CONTENT_TYPE'] = 'application/json';
        $request = '{"exercise": { "name": "Exercise updated", "reps": "4-5", "date": "2019-08-06"}}';
        $client = static::createClient();
        $client->request('PUT', '/exercises/12', [], [], $server, $request);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testFailedDeleteExercise()
    {
        $client = static::createClient();
        $client->request('DELETE', '/exercises/12');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testDeleteExercise()
    {
        $client = static::createClient();
        $client->request('DELETE', '/exercises/2');
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    }
}