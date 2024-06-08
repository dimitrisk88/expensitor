# Use the official Node.js image.
FROM node:22

# Set the working directory inside the container
WORKDIR /app

# Install React Native CLI and Yarn globally
RUN npm install -g react-native-cli

# Check if Yarn is installed and remove it if it exists, then install Yarn
RUN if [ ! -x "$(command -v yarn)" ]; then npm install -g yarn; fi

# Copy package.json and yarn.lock to the working directory
COPY package.json ./
RUN if [ -f ./yarn.lock ]; then cp ./yarn.lock /var/www/html/; fi

# Install dependencies
# RUN if [ -f /app/yarn.lock ]; then yarn install; else yarn upgrade --latest; fi
RUN yarn install

# Create metro.config.js file
RUN echo "const { getDefaultConfig } = require('metro-config');\n\nmodule.exports = (async () => {\n  const {\n    resolver: { sourceExts, assetExts }\n  } = await getDefaultConfig();\n  return {\n    transformer: {\n      babelTransformerPath: require.resolve('react-native-sass-transformer')\n    },\n    resolver: {\n      assetExts: assetExts.filter(ext => ext !== 'scss'),\n      sourceExts: [...sourceExts, 'scss']\n    }\n  };\n})();" > metro.config.js

# Expose port 8081 for Metro Bundler
EXPOSE 8081

# Start the Metro Bundler
CMD ["yarn", "start"]
