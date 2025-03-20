import './App.css'
import { Route, Routes } from 'react-router-dom'
import Navbar from './components/Navbar'
import Women from './pages/Women'

function App() {
  return (
    <>
      <Navbar />
      <Routes>
        <Route path='/women' element={<Women />}/>
        {/* <Route path='/men' element{<Products type='men' />}/> */}
        {/* <Route path='/children' element{<Products type='children' />}/> */}
      </Routes>
    </>
  )
}

export default App
