const AVATAR_BASE_URL = window.BASE_URL || document.body?.dataset.baseUrl || '';
const AVATAR_ICON_URL_DIR = AVATAR_BASE_URL + '/assets/icon/avatars/';

const AVATAR_GRADIENTS = {
    'grad-peach':    'radial-gradient(circle at 40% 35%, #FFF0E8, #F5CEB4)',
    'grad-sunset':   'radial-gradient(circle at 40% 35%, #FFE8D4, #F0B894)',
    'grad-amber':    'radial-gradient(circle at 40% 35%, #FFF4D0, #F0D484)',
    'grad-gold':     'radial-gradient(circle at 40% 35%, #FFFAE0, #F0E4A0)',
    'grad-coral':    'radial-gradient(circle at 40% 35%, #FFE0D8, #F0A898)',
    'grad-rose':     'radial-gradient(circle at 40% 35%, #FFE8F0, #F0B4CC)',
    'grad-blossom':  'radial-gradient(circle at 40% 35%, #FFD8F0, #F09CC8)',
    'grad-blush':    'radial-gradient(circle at 40% 35%, #F8ECF0, #E8C8D8)',
    'grad-sky':      'radial-gradient(circle at 40% 35%, #ECF4FF, #B8D4F8)',
    'grad-ocean':    'radial-gradient(circle at 40% 35%, #D8EEFF, #98C0F0)',
    'grad-dusk':     'radial-gradient(circle at 40% 35%, #E0E0FF, #A0A0F0)',
    'grad-aurora':   'radial-gradient(circle at 40% 35%, #F0D8FF, #C890F0)',
    'grad-lavender': 'radial-gradient(circle at 40% 35%, #F4ECFF, #D8C0F8)',
    'grad-mint':     'radial-gradient(circle at 40% 35%, #E8FFF4, #B0ECD4)',
    'grad-forest':   'radial-gradient(circle at 40% 35%, #D8F8EC, #90E0C0)',
    'grad-slate':    'radial-gradient(circle at 40% 35%, #ECF2F8, #C0D0E0)',
};

const AVATAR_DEFAULT = { color: 'grad-ocean', icon: 'rocket' };

function parseAvatar(str) {
    if (str && str.includes(':')) {
        const sep   = str.indexOf(':');
        const color = str.slice(0, sep);
        const icon  = str.slice(sep + 1);
        if (color && icon) return { color, icon };
    }
    return { color: AVATAR_DEFAULT.color, icon: AVATAR_DEFAULT.icon };
}

function avatarCssValue(color) {
    if (color.startsWith('#')) return color;
    return AVATAR_GRADIENTS[color] || AVATAR_DEFAULT.color;
}

function avatarIconUrl(icon) {
    return AVATAR_ICON_URL_DIR + encodeURIComponent(icon) + '.webp';
}

function applyAvatar(el, avatarStr, nome) {
    const { color, icon } = parseAvatar(avatarStr);
    el.classList.add('avatar-pattern');
    el.style.background = avatarCssValue(color);
    el.innerHTML = '<img src="' + avatarIconUrl(icon) + '" alt="" class="avatar-icon" loading="lazy">';
}
