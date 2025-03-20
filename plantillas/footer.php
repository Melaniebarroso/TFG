<style>
    footer {
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background-color: #222;
        padding: 15px 0;
        text-align: center;
        gap: 20px;
    }

    #nav-footer ul {
        list-style: none;
        padding: 0;
        display: flex;
        flex-direction: column;
        color: white;
        align-items: center;
        gap: 5px;
    }

    #nav-footer li {
        color: white;
        cursor: pointer;
    }

    #nav-footer li a {
        text-decoration: none;
        color: white;
    }

    #nav-footer li a:visited {
        text-decoration: none;
        color: white;
    }

    #nav-footer li a:hover {
        color:rgb(255, 54, 54);
    }
    h4 {
        font-family: "Principal";
        font-size: 15px;
        text-transform: uppercase;
        font-weight: 400;
        letter-spacing: 4px;
        color: white;
        padding: 0px;
        transition: all 0.4s ease-in-out;
    }
    footer p {
        font-family: "Principal";
        color: white;
    }
    footer input {
        padding: 10px;
        font-size: 16px;
        border: 2px solid #ccc;
        border-radius: 10px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

    footer input:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        outline: none;
    }

    #contact-button {
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        position: relative;
        text-decoration: none;
        padding: 0;
        display: inline-block;
        outline: none; 
    }

    #contact-button:visited,
    #contact-button:focus,
    #contact-button:hover {
        text-decoration: none; 
        color: white;
    }

</style>

<body>
    <footer>
        <section>
            <nav id="nav-footer">
                <ul>
                    <li><a href="/TFG/inicio/">Inicio</a></li>
                    <li><a href="/TFG/latara/">La Tará</a></li>
                    <li><a href="/TFG/educacion/">Educación</a></li>
                    <li><a href="/TFG/espectaculos/">Espectáculos</a></li>
                    <li><a href="/TFG/tienda/">Tienda</a></li>
                    <li><a href="/TFG/blog/">Blog</a></li>
                    <li><a href="/TFG/contacto/">Contacto</a></li>
                </ul>
            </nav>
        </section>
        <section>
            <div>
                <h4>¡Únete a la comunidad para estar informado de nuestras novedades!</h4>
                <form>
                    <input type="email" placeholder="Correo electrónico" required>
                </form>
            </div>
        </section>
        <section>
            <div>
            <img src="../resources/img/logo.png" class="logo">
                <p>¿Dudas?</p>
                <button id="contact-button" href="TFG/contacto/">¡Contacta aquí!</button>
            </div>
        </section>
    </footer>
</body>
