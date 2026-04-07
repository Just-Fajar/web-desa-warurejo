{{--
    WHATSAPP FLOATING ACTION BUTTON (FAB)
    
    Tombol floating WhatsApp untuk quick contact
    
    FEATURES:
    - Fixed position bottom-right corner
    - WhatsApp green gradient background
    - Hover animations (lift + scale)
    - Pulse animation untuk attract attention
    - WhatsApp icon SVG (official path)
    - Pre-filled message template
    - Direct link ke WhatsApp Web/App
    - Responsive sizing (mobile smaller)
    - Optional unread badge indicator
    
    LINK BEHAVIOR:
    - Opens WhatsApp with pre-filled message
    - Format: https://wa.me/{phone}?text={message}
    - Phone: 6283114796959 (Indonesia format)
    - Message: "Halo Admin Desa Warurejo, saya ingin bertanya"
    - target="_blank" - Opens in new tab
    - rel="noopener noreferrer" - Security best practice
    
    ANIMATIONS:
    - Pulse: 2s infinite (shadow expansion)
    - Hover: translateY(-4px) + scale(1.05)
    - Icon rotation on hover (10deg)
    - Smooth transitions (cubic-bezier easing)
    
    STYLING:
    - Size: 56x56px desktop, 48px mobile
    - Border-radius: 50% (perfect circle)
    - Gradient: #25D366 to #128C7E (WhatsApp brand colors)
    - Shadow: Glowing green shadow
    - Z-index: 90 (above most content, below modals)
    
    RESPONSIVE:
    - Desktop: 56px, bottom-24px right-24px
    - Tablet: 52px, bottom-20px right-20px
    - Mobile: 48px, bottom-16px right-16px
    - Icon scales proportionally
    
    OPTIONAL BADGE:
    - Unread notification badge (commented out)
    - Red circle top-right corner
    - Can display number (1, 2, 3+)
    - Uncomment to enable
    
    ACCESSIBILITY:
    - aria-label for screen readers
    - Sufficient color contrast
    - Keyboard focusable
    - Clear hover state
    
    VISIBILITY:
    - Only shown di homepage (controlled by layout)
    - Check: request()->routeIs('home')
    - Hidden on print (@media print)
    
    WHATSAPP NUMBER:
    - 6283114796959 (Admin Desa Warurejo)
    - Change di link href dan config jika berbeda
    
    USAGE:
    Included di public/layouts/app.blade.php:
    @if(request()->routeIs('home'))
        @include('public.partials.whatsapp-fab')
    @endif
--}}
{{-- WhatsApp Floating Action Button --}}
<a href="https://wa.me/62085168687700?text=Halo%20Admin%20Desa%20Warurejo,%20saya%20ingin%20bertanya" 
   target="_blank" 
   rel="noopener noreferrer"
   class="whatsapp-fab"
   aria-label="Hubungi via WhatsApp">
    <svg class="whatsapp-icon" viewBox="0 0 24 24" fill="currentColor">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
    </svg>
    
    {{-- Optional: Badge untuk unread notification --}}
    {{-- <span class="whatsapp-badge">1</span> --}}
</a>

<style>
.whatsapp-fab {
    position: fixed;
    bottom: 24px;
    right: 24px;
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 90;
    text-decoration: none;
    will-change: transform;
}

.whatsapp-fab:hover {
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 8px 20px rgba(37, 211, 102, 0.5);
    background: linear-gradient(135deg, #2EE874 0%, #15A88C 100%);
}

.whatsapp-fab:active {
    transform: translateY(-2px) scale(1.02);
}

.whatsapp-icon {
    width: 28px;
    height: 28px;
    color: white;
    transition: transform 0.3s ease;
}

.whatsapp-fab:hover .whatsapp-icon {
    transform: rotate(10deg);
}

/* Pulse animation */
@keyframes pulse {
    0% {
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
    }
    50% {
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.6), 0 0 0 8px rgba(37, 211, 102, 0.2);
    }
    100% {
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
    }
}

.whatsapp-fab {
    animation: pulse 2s infinite;
}

/* Optional badge style */
.whatsapp-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #FF3B30;
    color: white;
    font-size: 10px;
    font-weight: bold;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

/* Responsive */
@media (max-width: 768px) {
    .whatsapp-fab {
        width: 52px;
        height: 52px;
        bottom: 20px;
        right: 20px;
    }
    
    .whatsapp-icon {
        width: 26px;
        height: 26px;
    }
}

@media (max-width: 480px) {
    .whatsapp-fab {
        width: 48px;
        height: 48px;
        bottom: 16px;
        right: 16px;
    }
    
    .whatsapp-icon {
        width: 24px;
        height: 24px;
    }
}

/* Print: hide */
@media print {
    .whatsapp-fab {
        display: none !important;
    }
}
</style>
