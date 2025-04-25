const { builder } = require("@netlify/functions");
const { execSync } = require('child_process');

const manifest = {
    version: 1,
    routes: [
      {
        "path": "/*",
        "src": "/.netlify/functions/index"
      }
    ],
    bundlerConfig: {
      esbuild: {
        external: ["jquery"]
      }
    }
}

async function handler(event, context) {
  const phpFile = event.path.substring(1)
  try {
    const result = execSync(`php ${phpFile}`, {
      cwd: '/var/task/',
      env: {
        ...process.env,
        PATH: process.env.PATH + ':/opt/build/repo/. functions-sandbox/node_modules/.bin:/opt/build/repo/node_modules/.bin',
        PHPRC: '/opt/render/php.ini'
      },
      encoding: 'utf8'
    });
    
    return {
      statusCode: 200,
      headers: {
        "content-type": "text/html",
      },
      body: result,
    };
  } catch (error) {
    console.log(error)
    return { statusCode: 500, body: error.toString() };
  }
}

exports.handler = builder(handler);
exports.manifest = manifest