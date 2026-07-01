<?php

namespace App\Filament\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * Wires Filament resource authorization to Spatie permissions.
 *
 * Override the four permission methods in your resource to return the
 * appropriate permission string:
 *
 *   protected static function viewPermission(): string   { return 'view content'; }
 *   protected static function createPermission(): string { return 'create content'; }
 *   protected static function editPermission(): string   { return 'edit content'; }
 *   protected static function deletePermission(): string { return 'delete content'; }
 */
trait HasResourcePermissions
{
    protected static function viewPermission(): string   { return ''; }
    protected static function createPermission(): string { return ''; }
    protected static function editPermission(): string   { return ''; }
    protected static function deletePermission(): string { return ''; }

    public static function canViewAny(): bool
    {
        $perm = static::viewPermission();
        return $perm ? (auth()->user()?->can($perm) ?? false) : true;
    }

    public static function canCreate(): bool
    {
        $perm = static::createPermission();
        return $perm ? (auth()->user()?->can($perm) ?? false) : true;
    }

    public static function canEdit(Model $record): bool
    {
        $perm = static::editPermission();
        return $perm ? (auth()->user()?->can($perm) ?? false) : true;
    }

    public static function canDelete(Model $record): bool
    {
        $perm = static::deletePermission();
        return $perm ? (auth()->user()?->can($perm) ?? false) : true;
    }

    public static function canDeleteAny(): bool
    {
        $perm = static::deletePermission();
        return $perm ? (auth()->user()?->can($perm) ?? false) : true;
    }

    public static function canForceDelete(Model $record): bool
    {
        $perm = static::deletePermission();
        return $perm ? (auth()->user()?->can($perm) ?? false) : true;
    }

    public static function canForceDeleteAny(): bool
    {
        $perm = static::deletePermission();
        return $perm ? (auth()->user()?->can($perm) ?? false) : true;
    }

    public static function canRestore(Model $record): bool
    {
        $perm = static::editPermission();
        return $perm ? (auth()->user()?->can($perm) ?? false) : true;
    }

    public static function canRestoreAny(): bool
    {
        $perm = static::editPermission();
        return $perm ? (auth()->user()?->can($perm) ?? false) : true;
    }
}
