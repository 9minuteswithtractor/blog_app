import "./PageContainer.styles.scss";

const PageContainer = ({ greeting, content }) => {
  const userName = sessionStorage.getItem("userName");
  return (
    <main className="page-container">
      <div className="title-container">
        <h2>
          {userName ? `${greeting},  ${userName}!` : `${greeting}, Guest!`}
        </h2>
      </div>
      <div className="content-container">{content}</div>
    </main>
  );
};

export default PageContainer;
