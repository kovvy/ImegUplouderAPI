<?php

class ApiMethodsTest extends TestCase {
    /**
     * Test signup method
     *
     * @return void
     */
    public function testSignup()
    {
        $userId = time();
        $this->post('/api/v1/signup', ['user_id' => $userId])
            ->seeStatusCode(200)
            ->seeJson()
            ->seeJsonContains(['status' => 'success']);

        $this->post('/api/v1/signup', ['user_id' => $userId])
            ->seeStatusCode(409)
            ->seeJson()
            ->seeJsonContains(['status' => 'error', 'message' => 'User already exists']);

    }



}
