import "./PageContainer.styles.scss";

const PageContainer = ({ title, content }) => {
  return (
    <main className="page-container">
      <div className="title-container">
        <h2>{title}</h2>
      </div>
      <div className="content-container">{content}</div>
    </main>
  );
};

export default PageContainer;
