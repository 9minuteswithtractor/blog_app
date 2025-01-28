import axios from "axios";
import { useEffect, useState } from "react";

import "./ArticlesPage.styles.scss";

const ArticlesPage = () => {
  const [articles, setArticles] = useState([]);
  const [error, setError] = useState("");
  const [post, setPost] = useState(""); // For text input
  const [loading, setLoading] = useState(false); // Loading state for feedback

  // Session data
  const user = sessionStorage.getItem("userName");
  const loggedInStatus = sessionStorage.getItem("isLoggedIn") === "true"; //

  const handleOnPostChange = (event) => {
    setPost(event.target.value);
  };

  const handleOnFormSubmit = async (event) => {
    event.preventDefault();

    if (!post.trim()) {
      setError("Post content cannot be empty.");
      return;
    }

    const baseApi = "http://localhost:8000/api";
    try {
      const newArticle = {
        title: `Post by ${user}`,
        content: post,
        author: user,
      };

      await axios.post(`${baseApi}/articles/add`, newArticle, {
        headers: { "Content-Type": "application/json" },
        withCredentials: true, // Include cookies/session if needed
      });

      setPost("");
      fetchAllArticles();
      setError("");
    } catch (err) {
      setError(err.message);
    }
  };

  // Fetch articles on component loaded state
  useEffect(() => {
    fetchAllArticles();
  }, []);

  const fetchAllArticles = async () => {
    const baseApi = "http://localhost:8000/api";
    try {
      const response = await axios.get(`${baseApi}/articles`);
      setArticles(response.data);
    } catch (err) {
      setError(err.message);
    }
  };

  return (
    <div>
      <h1>Articles</h1>

      {loggedInStatus && (
        <form className="post-form" onSubmit={handleOnFormSubmit}>
          <div className="post-container">
            <input
              type="text"
              value={post}
              onChange={handleOnPostChange}
              placeholder="Write your article..."
            />
            <button type="submit" disabled={loading}>
              {loading ? "Posting..." : "Post"}
            </button>
          </div>
          {error && <p className="error-message">{error}</p>}
        </form>
      )}

      {/* Show loading or error for articles */}
      {loading && <p>Loading articles...</p>}
      {error && !loading && <p className="error-message">{error}</p>}

      {/* Display the articles */}
      {articles.length > 0 && !loading ? (
        <ul className="articles-container">
          {articles.map((article) => {
            const { id, title, content, author } = article;
            return (
              <li className="article-card-container" key={id}>
                <h2>{title}</h2>
                <p>{content}</p>
                <h3>
                  <em>{author}</em>
                </h3>
              </li>
            );
          })}
        </ul>
      ) : (
        !loading && <h2>No articles found or something went wrong...</h2>
      )}
    </div>
  );
};

export default ArticlesPage;
