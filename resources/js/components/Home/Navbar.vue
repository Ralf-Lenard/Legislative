<template>
    <header class="navbar">
        <div class="logo">
            <img
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3a/Concepcion_Tarlac_Seal.png/640px-Concepcion_Tarlac_Seal.png"
                alt="Concepcion Tarlac Seal"
            />
            <h1>Sangguniang Bayan</h1>
            <span class="municipality">Municipality of Concepcion, Tarlac</span>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="/home">Home</a></li>
                <li><a href="/sb">Sangguniang Bayan</a></li>
                <li><a href="/ordinances">Ordinances</a></li>
                <li><a href="/resolutions">Resolutions</a></li>
                <li><a href="#">About</a></li>
            </ul>
            <button class="menu-toggle">☰</button>
        </nav>
    </header>
</template>
<style scoped>
/* Color variables defined here for the Navbar */
.navbar {
    --primary-green: #1b5e20;
    --accent-gold: #ffc107;

    background-color: var(--primary-green);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 50px; /* Adjusted padding */
    border-radius: 0 0 15px 15px; /* Softer bottom curve */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Stronger shadow */
    font-family: 'Poppins', sans-serif;
}

.logo {
    display: flex;
    align-items: center;
    gap: 15px;
}

.logo img {
    width: 55px; /* Slightly smaller for elegance */
    border-radius: 50%;
    border: 2px solid var(--accent-gold); /* Gold accent ring */
}

.logo h1 {
    font-size: 1.5rem; /* Main title */
    font-weight: 700;
    margin: 0;
}

.logo .municipality {
    font-size: 0.8rem;
    font-weight: 300;
    opacity: 0.8;
    border-left: 1px solid rgba(255, 255, 255, 0.5);
    padding-left: 15px;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 35px; /* Wider spacing */
    margin: 0;
    padding: 0;
}

.navbar a {
    text-decoration: none;
    color: white;
    font-weight: 500;
    position: relative;
    transition: all 0.3s;
}

/* Underline effect on hover */
.navbar a::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    display: block;
    margin-top: 5px;
    right: 0;
    background: var(--accent-gold);
    transition: width 0.3s ease;
}

.navbar a:hover::after {
    width: 100%;
    left: 0;
    background: var(--accent-gold);
}

.navbar a:hover {
    color: var(--accent-gold);
}

.menu-toggle {
    display: none; /* Hide on desktop */
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
}

/* Responsive */
@media (max-width: 1024px) {
    .logo .municipality {
        display: none; /* Hide sub-text on medium screens */
    }
}

@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        align-items: flex-start;
        padding: 15px 20px;
    }

    .logo {
        margin-bottom: 10px;
    }

    .nav-links {
        flex-direction: column;
        width: 100%;
        gap: 10px;
        padding: 10px 0;
        /* In a real app, you would toggle 'display: none' based on menu-toggle state */
        /* For this CSS example, we'll keep it visible */
    }

    .nav-links li {
        text-align: left;
        padding: 5px 0;
    }
}
</style>
