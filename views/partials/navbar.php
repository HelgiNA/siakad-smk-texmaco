<header class="header">
    <button class="toggle-btn" id="sidebarToggle" aria-label="Toggle Menu">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>
    
    <div class="user-profile">
        <span>Hi, <?php echo $_SESSION["username"] ?? "Admin"; ?></span>
        <div class="user-avatar">
            <?php
            $initial = $_SESSION["username"][0] ?? "A";
            echo strtoupper($initial);
            ?>
        </div>
    </div>
</header>
