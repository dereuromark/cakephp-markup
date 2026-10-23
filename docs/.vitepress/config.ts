import { defineConfig } from 'vitepress'

const sidebar = [
  { text: 'Guide', items: [
    { text: 'Installation', link: '/guide/installation' },
    { text: 'Getting started', link: '/guide/getting-started' },
    { text: 'Markdown', link: '/guide/markdown' },
    { text: 'BBCode', link: '/guide/bbcode' },
    { text: 'Djot', link: '/guide/djot' },
    { text: 'Carve', link: '/guide/carve' },
    { text: 'Code highlighting', link: '/guide/highlighting' },
    { text: 'Markup templates', link: '/guide/templates' },
  ] },
  { text: 'Reference', items: [
    { text: 'Configuration', link: '/reference/configuration' },
    { text: 'Upgrading', link: '/reference/upgrading' },
  ] },
]

export default defineConfig({
  title: 'CakePHP Markup',
  description: 'Markdown, BBCode, Djot and code highlighting in CakePHP views.',
  base: '/cakephp-markup/',
  cleanUrls: true,
  lastUpdated: true,
  srcExclude: ['README.md', 'Markdown.md', 'Bbcode.md', 'Djot.md', 'Highlighter.md'],
  sitemap: { hostname: 'https://dereuromark.github.io/cakephp-markup/' },
  head: [['link', { rel: 'icon', href: '/cakephp-markup/favicon.svg', type: 'image/svg+xml' }]],
  themeConfig: {
    logo: '/logo.svg',
    nav: [
      { text: 'Installation', link: '/guide/installation' },
      { text: 'Getting started', link: '/guide/getting-started' },
      { text: 'Reference', link: '/reference/configuration' },
    ],
    sidebar,
    socialLinks: [{ icon: 'github', link: 'https://github.com/dereuromark/cakephp-markup' }],
    search: { provider: 'local' },
    editLink: { pattern: 'https://github.com/dereuromark/cakephp-markup/edit/master/docs/:path' },
    footer: { message: 'Released under the MIT License.', copyright: 'Copyright Mark Scherer' },
  },
})
