import '../styles/globals.css'

function MyApp({ Component, pageProps }) {
  return (
    <>
      <link href="https://netdna.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
      <div className="container">
        <Component {...pageProps} />
      </div>
    </>

  )

}

export default MyApp
