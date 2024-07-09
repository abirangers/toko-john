<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\MediaCrudController;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Log;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaControllerTest extends AdminTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $controller = new MediaCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_can_access_index_media_view()
    {
        $admin = User::factory()->create();
        $admin->syncRoles('admin');
        $admin->givePermissionTo('find-all-media');

        $response = $this->actingAs($admin)->get(route('admin.media.index'));

        $response->assertStatus(200);
    }

    public function test_user_cannot_access_index_media_view()
    {
        $user = User::factory()->create();
        $user->syncRoles('user');

        $response = $this->actingAs($user)->get(route('admin.media.index'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_index_media_view()
    {
        $response = $this->get(route('admin.media.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_delete_media()
    {
        $admin = User::factory()->create();
        $admin->syncRoles('admin');
        $admin->givePermissionTo('delete-media');

        $media = Media::create(
            [
                'id' => 1,
                'name' => 'example.jpg',
                'path' => 'example.jpg',
                'user_id' => $admin->id
            ]
        );

        Storage::put("public/images/products/example.jpg", "dummy content");

        $response = $this->actingAs($admin)->delete(route('admin.media.destroy', $media->id));

        $response->assertRedirect(route('admin.media.index'));
        $response->assertSessionHas('success', 'Media deleted successfully');
        $this->assertDatabaseMissing('media', ['id' => $media->id]);
        $this->assertFalse(Storage::exists("public/images/products/example.jpg"));
    }

    public function test_user_cannot_delete_media()
    {
        $user = User::factory()->create();
        $user->syncRoles('user');

        $media = Media::create(
            [
                'id' => 1,
                'name' => 'example.jpg',
                'path' => 'example.jpg',
                'user_id' => $user->id
            ]
        );

        Storage::put("public/images/products/example.jpg", "dummy content");

        $response = $this->actingAs($user)->delete(route('admin.media.destroy', $media->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_delete_media()
    {
        $user = User::factory()->create();

        $media = Media::create(
            [
                'id' => 1,
                'name' => 'example.jpg',
                'path' => 'example.jpg',
                'user_id' => $user->id
            ]
        );

        $user->delete();

        Storage::put("public/images/products/example.jpg", "dummy content");

        $response = $this->delete(route('admin.media.destroy', $media->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_bulk_delete_media()
    {
        $admin = User::factory()->create();
        $admin->syncRoles('admin');
        $admin->givePermissionTo('bulk-delete-media');

        $media1 = Media::create(['name' => 'example1.jpg', 'path' => 'example1.jpg', 'user_id' => $admin->id]);
        $media2 = Media::create(['name' => 'example2.jpg', 'path' => 'example2.jpg', 'user_id' => $admin->id]);

        Storage::put("public/images/products/example1.jpg", "dummy content");
        Storage::put("public/images/products/example2.jpg", "dummy content");

        $response = $this->actingAs($admin)->delete(route('admin.media.bulkDestroy'), [
            'ids' => [$media1->id, $media2->id]
        ]);

        Log::info($response->getStatusCode());
        Log::info($response->getContent());

        $response->assertRedirect(route('admin.media.index'));
        $response->assertSessionHas('success', 'Bulk media deleted successfully');
        $this->assertDatabaseMissing('media', ['id' => $media1->id]);
        $this->assertDatabaseMissing('media', ['id' => $media2->id]);
        $this->assertFalse(Storage::exists("public/images/products/example1.jpg"));
        $this->assertFalse(Storage::exists("public/images/products/example2.jpg"));
    }

    public function test_user_cannot_bulk_delete_media()
    {
        $user = User::factory()->create();
        $user->syncRoles('user');
        $user->givePermissionTo('bulk-delete-media');

        $media1 = Media::create(['name' => 'example1.jpg', 'path' => 'example1.jpg', 'user_id' => $user->id]);
        $media2 = Media::create(['name' => 'example2.jpg', 'path' => 'example2.jpg', 'user_id' => $user->id]);

        Storage::put("public/images/products/example1.jpg", "dummy content");
        Storage::put("public/images/products/example2.jpg", "dummy content");

        $response = $this->actingAs($user)->delete(route('admin.media.bulkDestroy'), [
            'ids' => [$media1->id, $media2->id]
        ]);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_bulk_delete_media()
    {
        $user = User::factory()->create();

        $media1 = Media::create(['name' => 'example1.jpg', 'path' => 'example1.jpg', 'user_id' => $user->id]);
        $media2 = Media::create(['name' => 'example2.jpg', 'path' => 'example2.jpg', 'user_id' => $user->id]);

        Storage::put("public/images/products/example1.jpg", "dummy content");
        Storage::put("public/images/products/example2.jpg", "dummy content");

        $user->delete();

        $response = $this->delete(route('admin.media.bulkDestroy'), [
            'ids' => [$media1->id, $media2->id]
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}