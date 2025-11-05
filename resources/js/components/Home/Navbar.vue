<template>
    <header class="navbar">
      <div class="logo">
        <img
          src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3a/Concepcion_Tarlac_Seal.png/640px-Concepcion_Tarlac_Seal.png"
          alt="Concepcion Tarlac Seal"
        />
        <h1>Municipality of Concepcion, Tarlac</h1>
      </div>
      <nav>
        <ul>
          <li><router-link to="/">Home</router-link></li>
          <li><router-link to="/sb">Sangguniang Bayan</router-link></li>
          <li><router-link to="/ordinances">Ordinances</router-link></li>
          <li><router-link to="/resolutions">Resolutions</router-link></li>
          <li><router-link to="/about">About</router-link></li>
        </ul>
      </nav>
    </header>
  </template>
  
  <style scoped>
  .navbar {
    background: linear-gradient(90deg, #1b5e20, #388e3c);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 40px;
    border-radius: 0 0 25px 25px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  }
  
  .logo {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .logo img {
    width: 60px;
    border-radius: 50%;
  }
  
  .navbar ul {
    list-style: none;
    display: flex;
    gap: 25px;
  }
  
  .navbar a {
    text-decoration: none;
    color: white;
    font-weight: 500;
    transition: color 0.3s;
  }
  
  .navbar a:hover {
    color: #ffd700;
  }
  
  @media (max-width: 768px) {
    .navbar {
      flex-direction: column;
    }
  }
  </style>
  