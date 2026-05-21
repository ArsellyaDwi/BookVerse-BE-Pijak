<?php

namespace App\Helpers;

use App\Models\ContactMessage;

class MenuHelper
{
    public static function getMainNavItems()
    {
        $unreadCount = 0;
        if (ContactMessage::count() > 0) {
            $unreadCount = ContactMessage::where('status', 'unread')->count();
        }
        return [
            [
                'icon' => 'dashboard',
                'name' => 'Dashboard',
                'path' => '/dashboard',
            ],
            [
                'icon' => 'ai-assistant',
                'name' => 'AI',
                'subItems' => [
                    // ['name' => 'Emotion Dataset', 'path' => '/ai/emotion-datasets'],
                    ['name' => 'AI Emotion Rule', 'path' => '/ai/emotion-rules'],
                    ['name' => 'AI Recommendation Logs', 'path' => '/ai/recommendation-logs'],
                ],
            ],
            [
                'icon' => 'ecommerce',
                'name' => 'Catalog',
                'subItems' => [
                    ['name' => 'Books', 'path' => '/books'],
                    ['name' => 'Genres', 'path' => '/genres'],
                    ['name' => 'Characters', 'path' => '/characters'],
                ],
            ],
            [
                'icon' => 'blog',
                'name' => 'Blog',
                'subItems' => [
                    ['name' => 'All Posts', 'path' => '/blogs'],
                ],
            ],
            [
                'icon' => 'sales',
                'name' => 'Sales',
                'subItems' => [
                    ['name' => 'Transaction', 'path' => '/transactions'],
                    ['name' => 'Payment Methods', 'path' => '/payment-methods'],
                    ['name' => 'Delivery Methods', 'path' => '/delivery-methods'],
                ],
            ],
            [
                'icon' => 'user-profile',
                'name' => 'Customers',
                'path' => '/customers',
            ],
            [
                'icon' => 'envelope',
                'name' => 'Contact Messages',
                'path' => '/contact-messages',
                'badge' => $unreadCount > 0 ? $unreadCount : null,
            ],
            [
                'icon' => 'settings',
                'name' => 'Store Setting',
                'path' => '/settings',
            ]
        ];
    }

    public static function getMenuGroups()
    {
        return [
            [
                'title' => 'Menu',
                'items' => self::getMainNavItems()
            ]
        ];
    }

    public static function isActive($path)
    {
        return request()->is(ltrim($path, '/'));
    }

    public static function getIconSvg($iconName)
    {
        $icons = [
            'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>',

            'ai-assistant' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.75 2.42969V7.70424M9.42261 13.673C10.0259 14.4307 10.9562 14.9164 12 14.9164C13.0438 14.9164 13.9742 14.4307 14.5775 13.673M20 12V18.5C20 19.3284 19.3284 20 18.5 20H5.5C4.67157 20 4 19.3284 4 18.5V12C4 7.58172 7.58172 4 12 4C16.4183 4 20 7.58172 20 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M18.75 2.42969V2.43969M9.50391 9.875L9.50391 9.885M14.4961 9.875V9.885" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>',

            'ecommerce' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.31641 4H3.49696C4.24468 4 4.87822 4.55068 4.98234 5.29112L5.13429 6.37161M5.13429 6.37161L6.23641 14.2089C6.34053 14.9493 6.97407 15.5 7.72179 15.5L17.0833 15.5C17.6803 15.5 18.2205 15.146 18.4587 14.5986L21.126 8.47023C21.5572 7.4795 20.8312 6.37161 19.7507 6.37161H5.13429Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M7.7832 19.5H7.7932M16.3203 19.5H16.3303" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>',

            'user-profile' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z" fill="currentColor"></path></svg>',

            'sales' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 9L12 3L21 9L12 15L3 9Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"></path><path d="M5 13V18M9 15V18M15 15V18M19 13V18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path><path d="M12 15V21M8 21H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path></svg>',

            'settings' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.098 3.00007C10.5537 3.00007 10.0715 3.36227 9.92014 3.88287L9.46421 5.38443C9.13069 5.39459 8.8049 5.43137 8.48873 5.49355L7.15149 4.44482C6.71179 4.09925 6.08313 4.15389 5.70677 4.53025L4.52914 5.70788C4.15278 6.08424 4.09814 6.7129 4.44371 7.1526L5.49244 8.48984C5.43026 8.80601 5.39348 9.1318 5.38332 9.46532L3.88176 9.92125C3.36116 10.0726 2.99896 10.5548 2.99896 11.0991V12.9009C2.99896 13.4452 3.36116 13.9274 3.88176 14.0788L5.38332 14.5347C5.39348 14.8682 5.43026 15.194 5.49244 15.5102L4.44371 16.8474C4.09814 17.2871 4.15278 17.9158 4.52914 18.2921L5.70677 19.4698C6.08313 19.8461 6.71179 19.9008 7.15149 19.5552L8.48873 18.5065C8.8049 18.5686 9.13069 18.6054 9.46421 18.6156L9.92014 20.1171C10.0715 20.6377 10.5537 20.9999 11.098 20.9999H12.8999C13.4442 20.9999 13.9264 20.6377 14.0778 20.1171L14.5337 18.6156C14.8672 18.6054 15.193 18.5686 15.5092 18.5065L16.8464 19.5552C17.2861 19.9008 17.9148 19.8461 18.2911 19.4698L19.4688 18.2921C19.8451 17.9158 19.8998 17.2871 19.5542 16.8474L18.5055 15.5102C18.5676 15.194 18.6044 14.8682 18.6146 14.5347L20.1161 14.0788C20.6367 13.9274 20.9989 13.4452 20.9989 12.9009V11.0991C20.9989 10.5548 20.6367 10.0726 20.1161 9.92125L18.6146 9.46532C18.6044 9.1318 18.5676 8.80601 18.5055 8.48984L19.5542 7.1526C19.8998 6.7129 19.8451 6.08424 19.4688 5.70788L18.2911 4.53025C17.9148 4.15389 17.2861 4.09925 16.8464 4.44482L15.5092 5.49355C15.193 5.43137 14.8672 5.39459 14.5337 5.38443L14.0778 3.88287C13.9264 3.36227 13.4442 3.00007 12.8999 3.00007H11.098ZM9.24896 12C9.24896 10.4808 10.4797 9.25007 11.9989 9.25007C13.5182 9.25007 14.7489 10.4808 14.7489 12C14.7489 13.5193 13.5182 14.7501 11.9989 14.7501C10.4797 14.7501 9.24896 13.5193 9.24896 12ZM11.9989 7.75007C9.6518 7.75007 7.74896 9.65291 7.74896 12C7.74896 14.3472 9.6518 16.2501 11.9989 16.2501C14.3461 16.2501 16.2489 14.3472 16.2489 12C16.2489 9.65291 14.3461 7.75007 11.9989 7.75007Z" fill="currentColor"/></svg>',

            'envelope' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 8L12 13L21 8M5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7V17C3 18.1046 3.89543 19 5 19Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>',

            'blog' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M4 4H20V20H4V4Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M8 7H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
    <path d="M8 12H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
    <path d="M8 17H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
    <path d="M17 17H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
</svg>',
        ];
        return $icons[$iconName] ?? '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="currentColor"/></svg>';
    }
}
