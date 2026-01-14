# Minimalist Design Redesign - TODO

## Overview
Redesign Chatbot, Register, and Login pages with a clean, minimalist aesthetic using soft, calming colors appropriate for maternal care.

---

## Phase 1: Chatbot Redesign

### Files to Edit:
- [x] `src/resources/views/livewire/chatbot.blade.php` - Add minimize/expand functionality, clean layout
- [x] `src/public/front/css/chat.css` - New minimalist color palette, collapsible sidebar
- [x] `src/public/front/js/chat.js` - Add minimize/expand JavaScript

### Design Changes:
- [x] 1.1 Remove neon glow effects (cyan, green, purple)
- [x] 1.2 New color palette: soft pinks, warm creams, gentle greens
- [x] 1.3 Collapsible sidebar (280px → collapsed state)
- [x] 1.4 Floating minimize/expand button
- [x] 1.5 Clean message bubbles with soft shadows
- [x] 1.6 Smooth animations/transitions
- [x] 1.7 Better typography and spacing

---

## Phase 2: Register Page Redesign

### Files to Edit:
- [x] `src/resources/views/livewire/register.blade.php` - Clean up HTML, remove emoji from title
- [x] `src/public/front/css/app.css` - New minimalist styles for register

### Design Changes:
- [x] 2.1 Remove dark neon theme
- [x] 2.2 Clean white/cream card design
- [x] 2.3 Soft shadows instead of glassmorphism
- [x] 2.4 Simple, elegant typography
- [x] 2.5 Clean form styling
- [x] 2.6 Smooth hover effects on buttons
- [x] 2.7 Better form spacing

---

## Phase 3: Login Page Redesign

### Files to Edit:
- [x] `src/resources/views/livewire/login.blade.php` - Clean up HTML, remove emoji from title
- [x] `src/public/front/css/app.css` - New minimalist styles for login

### Design Changes:
- [x] 3.1 Match register page style
- [x] 3.2 Clean card design
- [x] 3.3 Clean form styling
- [x] 3.4 Consistent button styling
- [x] 3.5 Smooth transitions

---

## ✅ COMPLETED - All tasks finished!

---

## Color Palette (Soft Maternal Theme)

### Primary Colors:
- Soft Pink: #f9a8d4 (light), #ec4899 (accent)
- Warm Cream: #fffbeb (background)
- Sage Green: #a7f3d0 (success/online)
- Dark Gray: #374151 (text)

### CSS Variables to Create:
```css
:root {
    --bg-primary: #fffbeb;
    --bg-card: #ffffff;
    --text-primary: #374151;
    --text-secondary: #6b7280;
    --accent-pink: #f472b6;
    --accent-green: #34d399;
    --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.08);
    --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.12);
}
```

---

## Implementation Steps:

### Step 1: Update chat.css
- [ ] Define new CSS variables
- [ ] Create collapsible sidebar styles
- [ ] Design minimize/expand button
- [ ] Style message bubbles
- [ ] Add smooth transitions

### Step 2: Update chatbot.blade.php
- [ ] Add minimize button to header
- [ ] Add sidebar toggle button
- [ ] Clean up HTML structure
- [ ] Improve message layout

### Step 3: Update chat.js
- [ ] Add sidebar toggle functionality
- [ ] Add minimize/expand logic
- [ ] Smooth scroll behavior

### Step 4: Update app.css (Register)
- [ ] New minimalist card styles
- [ ] Clean form input styling
- [ ] Smooth button effects

### Step 5: Update register.blade.php
- [ ] Remove emoji from title
- [ ] Clean HTML structure

### Step 6: Update app.css (Login)
- [ ] Match register page styles
- [ ] Consistent design language

### Step 7: Update login.blade.php
- [ ] Remove emoji from title
- [ ] Clean HTML structure

---

## Status: READY TO START

