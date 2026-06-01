<?php

// Todos degradês concêntricos (radial do centro claro para borda levemente mais rica)
define('AVATAR_GRADIENTS', [
    'grad-peach'    => 'radial-gradient(circle at 40% 35%, #FFF0E8, #F5CEB4)',
    'grad-sunset'   => 'radial-gradient(circle at 40% 35%, #FFE8D4, #F0B894)',
    'grad-amber'    => 'radial-gradient(circle at 40% 35%, #FFF4D0, #F0D484)',
    'grad-gold'     => 'radial-gradient(circle at 40% 35%, #FFFAE0, #F0E4A0)',
    'grad-coral'    => 'radial-gradient(circle at 40% 35%, #FFE0D8, #F0A898)',
    'grad-rose'     => 'radial-gradient(circle at 40% 35%, #FFE8F0, #F0B4CC)',
    'grad-blossom'  => 'radial-gradient(circle at 40% 35%, #FFD8F0, #F09CC8)',
    'grad-blush'    => 'radial-gradient(circle at 40% 35%, #F8ECF0, #E8C8D8)',
    'grad-sky'      => 'radial-gradient(circle at 40% 35%, #ECF4FF, #B8D4F8)',
    'grad-ocean'    => 'radial-gradient(circle at 40% 35%, #D8EEFF, #98C0F0)',
    'grad-dusk'     => 'radial-gradient(circle at 40% 35%, #E0E0FF, #A0A0F0)',
    'grad-aurora'   => 'radial-gradient(circle at 40% 35%, #F0D8FF, #C890F0)',
    'grad-lavender' => 'radial-gradient(circle at 40% 35%, #F4ECFF, #D8C0F8)',
    'grad-mint'     => 'radial-gradient(circle at 40% 35%, #E8FFF4, #B0ECD4)',
    'grad-forest'   => 'radial-gradient(circle at 40% 35%, #D8F8EC, #90E0C0)',
    'grad-slate'    => 'radial-gradient(circle at 40% 35%, #ECF2F8, #C0D0E0)',
]);

define('AVATAR_DEFAULT_COLOR', 'grad-ocean');
define('AVATAR_DEFAULT_ICON',  'rocket');

define('AVATAR_ICON_URL_DIR', '/assets/icon/avatars/');
define('AVATAR_ICON_FS_DIR',  __DIR__ . '/../assets/icon/avatars/');

function avatarCssValue(string $color): string
{
    if (str_starts_with($color, '#')) return $color;
    $gradients = AVATAR_GRADIENTS;
    return $gradients[$color] ?? AVATAR_DEFAULT_COLOR;
}

function avatarAllIcons(): array
{
    static $cache = null;
    if ($cache !== null) return $cache;
    $files = glob(AVATAR_ICON_FS_DIR . '*.webp') ?: [];
    $cache = array_map(fn($f) => str_replace('.webp', '', basename($f)), $files);
    sort($cache);
    return $cache;
}

function avatarIconUrl(string $icon): string
{
    return BASE_URL . AVATAR_ICON_URL_DIR . rawurlencode($icon) . '.webp';
}

function parseAvatar(string $str): array
{
    if ($str && str_contains($str, ':')) {
        $sep   = strpos($str, ':');
        $color = substr($str, 0, $sep);
        $icon  = substr($str, $sep + 1);
        $validColor = str_starts_with($color, '#') || isset(AVATAR_GRADIENTS[$color]);
        if ($icon && $validColor && file_exists(AVATAR_ICON_FS_DIR . $icon . '.webp')) {
            return ['color' => $color, 'icon' => $icon];
        }
    }
    return ['color' => AVATAR_DEFAULT_COLOR, 'icon' => AVATAR_DEFAULT_ICON];
}

function avatarInitials(string $nome): string
{
    $words = array_values(array_filter(explode(' ', trim($nome))));
    if (count($words) >= 2) {
        return mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[count($words) - 1], 0, 1));
    }
    return mb_strtoupper(mb_substr($nome, 0, 2));
}

function renderAvatar(string $avatarStr, string $nome, string $cssClass = ''): string
{
    $a        = parseAvatar($avatarStr);
    $bgCss    = htmlspecialchars(avatarCssValue($a['color']), ENT_QUOTES);
    $initials = htmlspecialchars(avatarInitials($nome), ENT_QUOTES);
    $iconUrl  = htmlspecialchars(avatarIconUrl($a['icon']), ENT_QUOTES);
    $cls      = 'avatar-pattern' . ($cssClass ? ' ' . htmlspecialchars($cssClass) : '');
    return "<div class=\"{$cls}\" style=\"background:{$bgCss}\" aria-label=\"{$initials}\">"
         . "<img src=\"{$iconUrl}\" alt=\"{$initials}\" class=\"avatar-icon\" loading=\"lazy\">"
         . "</div>";
}

function avatarPickerHtml(string $selected = '', string $inputName = 'avatar'): string
{
    $a       = parseAvatar($selected ?: AVATAR_DEFAULT_COLOR . ':' . AVATAR_DEFAULT_ICON);
    $value   = $a['color'] . ':' . $a['icon'];
    $bgCss   = avatarCssValue($a['color']);
    $icons   = avatarAllIcons();
    $grads   = AVATAR_GRADIENTS;

    ob_start(); ?>
    <div class="avatar-picker">
        <input type="hidden" name="<?= htmlspecialchars($inputName) ?>" class="avatar-picker-value" value="<?= htmlspecialchars($value) ?>">

        <!-- Preview -->
        <div class="avatar-picker-preview">
            <div class="avatar-pattern avatar-picker-preview-avatar" style="background:<?= htmlspecialchars($bgCss) ?>">
                <img src="<?= htmlspecialchars(avatarIconUrl($a['icon'])) ?>" alt="" class="avatar-icon avatar-picker-preview-icon" loading="lazy">
            </div>
            <span class="avatar-picker-preview-label"><?= htmlspecialchars($a['icon']) ?></span>
        </div>

        <!-- Degradês -->
        <div class="avatar-picker-section">
            <label class="avatar-picker-label">Cor de fundo</label>
            <div class="avatar-picker-colors">
                <?php foreach ($grads as $key => $css): ?>
                <button type="button"
                        class="avatar-picker-color<?= $key === $a['color'] ? ' avatar-picker-color--selected' : '' ?>"
                        data-color="<?= htmlspecialchars($key) ?>"
                        style="background:<?= htmlspecialchars($css) ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Ícone -->
        <div class="avatar-picker-section">
            <label class="avatar-picker-label">Ícone</label>
            <button type="button" class="avatar-picker-toggle-icons">
                <img src="<?= htmlspecialchars(avatarIconUrl($a['icon'])) ?>" alt="" class="avatar-picker-toggle-thumb" loading="lazy">
                <span class="avatar-picker-toggle-text">Escolher ícone</span>
                <svg class="avatar-picker-toggle-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>

            <div class="avatar-picker-icons-panel" hidden>
                <input type="text" class="avatar-picker-search" placeholder="Buscar ícone..." autocomplete="off">
                <div class="avatar-picker-icons-grid">
                    <?php foreach ($icons as $icon): ?>
                    <button type="button"
                            class="avatar-picker-icon<?= $icon === $a['icon'] ? ' avatar-picker-icon--selected' : '' ?>"
                            data-icon="<?= htmlspecialchars($icon) ?>"
                            title="<?= htmlspecialchars($icon) ?>">
                        <img src="<?= htmlspecialchars(avatarIconUrl($icon)) ?>" alt="<?= htmlspecialchars($icon) ?>" loading="lazy">
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php return ob_get_clean();
}
