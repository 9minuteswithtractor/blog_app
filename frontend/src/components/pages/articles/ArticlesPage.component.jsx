import axios from "axios";
import { useEffect, useState } from "react";

import "./ArticlesPage.styles.scss";

const ArticlesPage = () => {
  const [articles, setArticles] = useState([]);
  const [error, setError] = useState("");

  // fetch all articles on init state :
  useEffect(() => {
    const fetchAllArticles = async () => {
      const baseApi = "http://localhost:8000/api";
      try {
        const response = await axios.get(`${baseApi}/articles`);

        const allReqData = response.data;
        setArticles(allReqData);
        //
      } catch (err) {
        setError(err.message); // Update state with the error message
      } finally {
        console.log("fetching all articles from server ...");
      }
    };
    fetchAllArticles(); // Call the function
  }, []);

  // obj shape : id, title, content, author
  if (articles.length > 0 && !error) {
    return (
      <>
        <ul className="articles-container">
          {articles.map((post) => {
            const { id, title, content, author } = post;
            return (
              <>
                <li className="article-card-container" key={id}>
                  <h2>{title}</h2>
                  <p>{content}</p>
                  <h3>
                    <em>{author}</em>
                  </h3>
                </li>
                <li className="article-card-container" key={id}>
                  <h2>{title}</h2>
                  <p>{content}</p>
                  <h3>
                    <em>{author}</em>
                  </h3>
                </li>
              </>
            );
          })}
        </ul>
      </>
    );
  } else {
    return <h2>No articles found or something went wrong ...{error}</h2>;
  }
};

export default ArticlesPage;
