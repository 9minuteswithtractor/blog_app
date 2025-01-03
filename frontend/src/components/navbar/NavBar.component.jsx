import { Link } from "react-router-dom";
import "./NavBar.styles.scss";

const NavBar = () => {
  return (
    <header className="nav-bar-container">
      <img src="logo.png" alt="Logo" />
      <nav className="navbar">
        <ul>
          <li className="navigation-right">
            <Link to="/">Home</Link>
          </li>
          <li className="navigation-right">
            <Link to="/posts">Articles</Link>
          </li>
          <li className="navigation-login">
            <Link to="/login">
              <i class="bi bi-person-fill"></i> Login
            </Link>
          </li>
        </ul>
      </nav>
    </header>
  );
};

export default NavBar;
