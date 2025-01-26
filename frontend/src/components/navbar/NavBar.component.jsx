import { Link, useNavigate } from "react-router-dom";
import "./NavBar.styles.scss";
import { useEffect, useState } from "react";
import axios from "axios";

const NavBar = ({ userName, setUserName }) => {
  const navigate = useNavigate();

  const handleOnLogout = async () => {
    setUserName(null);
    sessionStorage.clear();
    navigate("/login");
  };

  useEffect(() => {
    setUserName(sessionStorage.getItem("userName"));
  }, [userName]);

  return (
    <header className="nav-bar-container">
      <div className="nav-bar-content">
        <img src="..jpg" alt="< YourLogoHere />" className="nav-logo" />
        <nav className="nav-links">
          <ul>
            <li>
              <Link to="/">Home</Link>
            </li>
            <li>
              <Link to="/articles">Articles</Link>
            </li>
          </ul>
        </nav>
        <div className="nav-user-actions">
          {userName ? (
            <>
              <span className="nav-user-name">
                <i className="bi bi-person-fill"></i> {userName}
              </span>
              <button className="logout-button" onClick={handleOnLogout}>
                Logout
              </button>
            </>
          ) : (
            <Link to="/login" className="nav-login-link">
              <i className="bi bi-person-fill"></i> Login
            </Link>
          )}
        </div>
      </div>
    </header>
  );
};

export default NavBar;
