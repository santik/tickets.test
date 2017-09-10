<?php
declare(strict_types=1);

namespace Santik\Tickets\Application;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Santik\Tickets\Domain\Repository\UserRepository;
use Santik\Tickets\Domain\User;
use Symfony\Component\HttpFoundation\Request;

class UserServiceTest extends TestCase
{
    public function testFindOrCreate_withExistingId_WillReturnUser()
    {
        $id = 123;
        $expectedUser = User::createFromArray(['id' => $id, 'name' => 'name']);

        $repository = $this->prophesize(UserRepository::class);
        $repository->findUser($id)->willReturn($expectedUser);
        $service = new UserService($repository->reveal());

        $this->assertEquals($expectedUser, $service->findOrCreateUser($id));
    }

    public function testFindOrCreate_withNewId_WillReturnUser()
    {
        $id = 123;
        $expectedUser = User::createFromArray(['id' => $id, 'name' => 'name']);

        $repository = $this->prophesize(UserRepository::class);
        $repository->findUser($id)->willThrow(\Exception::class);
        $repository->createUser(Argument::any())->willReturn($expectedUser);
        $service = new UserService($repository->reveal());

        $this->assertEquals($expectedUser, $service->findOrCreateUser($id));
    }

    public function testGetUserFromRequest_withExistingUserId_WillReturnUserWithThisId()
    {
        $id = 123;
        $expectedUser = User::createFromArray(['id' => $id, 'name' => 'name']);

        $request = $this->prophesize(Request::class);
        $data = json_encode(['userId' => $id]);
        $request->getContent()->willReturn($data);

        $repository = $this->prophesize(UserRepository::class);
        $repository->findUser($id)->willReturn($expectedUser);
        $service = new UserService($repository->reveal());

        $user = $service->getUserFromRequest($request->reveal());

        $this->assertEquals($expectedUser, $user);
    }

    public function testGetUserFromRequest_userNotFound_WillThrowException()
    {
        $id = 123;

        $request = $this->prophesize(Request::class);
        $data = json_encode(['userId' => $id]);
        $request->getContent()->willReturn($data);

        $repository = $this->prophesize(UserRepository::class);
        $repository->findUser($id)->willThrow(\Exception::class);
        $service = new UserService($repository->reveal());

        $this->expectException(\Exception::class);

        $service->getUserFromRequest($request->reveal());
    }
}
