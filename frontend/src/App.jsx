import { BrowserRouter as Router, Route, Routes, Link } from "react-router-dom";
import NavBar from "./components/navbar/NavBar.component";
import "./App.scss";
import "./components/navbar/NavBar.styles.scss";

function App() {
  return (
    <div className="App">
      <Router>
        <div>
          <NavBar />
          <Routes>
            <Route path="/" element={<h3>Home</h3>} />
            <Route path="/posts" element={<h3>Posts</h3>} />
            <Route path="/login" element={<h3>Login</h3>} />
          </Routes>
        </div>
      </Router>
    </div>
  );
}

export default App;
