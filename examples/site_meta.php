<?php

class SiteMetaManager {
    private array $metaList = [];

    public function __construct() {
        $this->metaList = [
            'home' => [
                'title' => '乐鱼体育',
                'siteUrl' => 'https://sitehome-leyu.com.cn',
                'description' => '专业体育资讯与娱乐平台',
                'language' => 'zh-CN',
                'keywords' => ['乐鱼体育', '体育新闻', '赛事直播', '体育娱乐'],
                'author' => 'LeYu Sports Group'
            ],
            'about' => [
                'title' => '关于我们 - 乐鱼体育',
                'siteUrl' => 'https://sitehome-leyu.com.cn/about',
                'description' => '乐鱼体育致力于提供最新体育赛事资讯、深度分析和娱乐互动',
                'language' => 'zh-CN',
                'keywords' => ['乐鱼体育', '公司介绍', '体育文化'],
                'author' => 'LeYu Sports Group'
            ],
            'contact' => [
                'title' => '联系我们 - 乐鱼体育',
                'siteUrl' => 'https://sitehome-leyu.com.cn/contact',
                'description' => '乐鱼体育客户服务与商务合作联系通道',
                'language' => 'zh-CN',
                'keywords' => ['乐鱼体育', '联系方式', '客服'],
                'author' => 'LeYu Sports Group'
            ]
        ];
    }

    public function generateDescription(string $pageKey): string {
        if (!isset($this->metaList[$pageKey])) {
            return '';
        }
        $meta = $this->metaList[$pageKey];
        $kwString = implode(', ', $meta['keywords']);
        $desc = sprintf(
            '%s - %s | 关键词: %s | 来源: %s',
            $meta['title'],
            $meta['description'],
            $kwString,
            $meta['siteUrl']
        );
        return htmlspecialchars($desc, ENT_QUOTES, 'UTF-8');
    }

    public function getMetaData(string $pageKey): ?array {
        return $this->metaList[$pageKey] ?? null;
    }

    public function getAllPages(): array {
        return array_keys($this->metaList);
    }

    public function addPage(string $key, array $metaData): bool {
        $required = ['title', 'siteUrl', 'description', 'language', 'keywords', 'author'];
        foreach ($required as $field) {
            if (!array_key_exists($field, $metaData)) {
                return false;
            }
        }
        $this->metaList[$key] = $metaData;
        return true;
    }

    public function renderMetaTags(string $pageKey): string {
        $meta = $this->getMetaData($pageKey);
        if (!$meta) {
            return '';
        }
        $tags = sprintf(
            '<meta charset="UTF-8">' . "\n" .
            '<meta name="description" content="%s">' . "\n" .
            '<meta name="keywords" content="%s">' . "\n" .
            '<meta name="author" content="%s">' . "\n" .
            '<meta name="language" content="%s">' . "\n" .
            '<base href="%s">',
            htmlspecialchars($meta['description'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars(implode(', ', $meta['keywords']), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($meta['author'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($meta['language'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($meta['siteUrl'], ENT_QUOTES, 'UTF-8')
        );
        return $tags;
    }

    public function listAllDescriptions(): array {
        $result = [];
        foreach ($this->metaList as $key => $meta) {
            $result[$key] = $this->generateDescription($key);
        }
        return $result;
    }
}

// 示例使用
$metaManager = new SiteMetaManager();
echo "=== 简短描述文本 ===\n";
echo $metaManager->generateDescription('home') . "\n\n";

echo "=== 所有页面描述 ===\n";
foreach ($metaManager->listAllDescriptions() as $page => $desc) {
    echo "[{$page}] {$desc}\n";
}
echo "\n";

echo "=== HTML Meta 标签 (首页) ===\n";
echo $metaManager->renderMetaTags('home') . "\n\n";

echo "=== 新增自定义页面 ===\n";
$metaManager->addPage('faq', [
    'title' => '常见问题 - 乐鱼体育',
    'siteUrl' => 'https://sitehome-leyu.com.cn/faq',
    'description' => '乐鱼体育平台常见问题解答',
    'language' => 'zh-CN',
    'keywords' => ['乐鱼体育', 'FAQ', '帮助中心'],
    'author' => 'LeYu Sports Group'
]);
echo $metaManager->generateDescription('faq') . "\n";