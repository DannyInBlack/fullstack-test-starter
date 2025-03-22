import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import { BrowserRouter } from 'react-router-dom' // Import BrowserRouter
import { ApolloClient, InMemoryCache, ApolloProvider } from '@apollo/client';
import './index.css'
import App from './App.tsx'

const client = new ApolloClient({
    uri: 'http://16.171.33.140/fullstack-test-starter/graphql',
    cache: new InMemoryCache(),
});

createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <BrowserRouter>
      <ApolloProvider client={client}>
        <App />
      </ApolloProvider>
    </BrowserRouter>
  </StrictMode>,
)