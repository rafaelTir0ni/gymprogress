<?php
declare(strict_types=1);

/** Apenas arquivos privados gerados pelo servidor podem ser usados. */
function profileImageDirectory(): string
{
    return dirname(__DIR__, 2) . '/storage/profile-images';
}

function profileImageName(?string $name): ?string
{
    return is_string($name) && preg_match('/\Aavatar_[a-f0-9]{32}\.jpg\z/D', $name) ? $name : null;
}

function profileAvatarMarkup(?string $image, string $name, string $class = 'gp-avatar'): string
{
    $first = mb_strtoupper(mb_substr(trim($name), 0, 1));
    $letter = e($first !== '' ? $first : 'G');
    $img = profileImageName($image);
    $inner = $img ? '<img src="' . e(routeUrl('perfil-foto')) . '" alt="" loading="lazy">' : $letter;
    return '<span class="' . e($class) . '" aria-hidden="true">' . $inner . '</span>';
}
