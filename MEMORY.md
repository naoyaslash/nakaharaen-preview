# 中原苑プロジェクト — MEMORY

## リポジトリ・URL
- **GitHub リポジトリ**: naoyaslash/nakaharaen-preview（public）
- **プレビューURL**: https://naoyaslash.github.io/nakaharaen-preview/
- **管理画面デモ**: https://naoyaslash.github.io/nakaharaen-preview/admin/index.html
- **現行サイト**: https://nakaharaen.com（WordPress）
- **ローカルパス**: `/Users/naoya/Desktop/AI-Workspace/02_projects/nakaharaen/`

## クライアント情報
- **施設名**: 特別養護老人ホーム 中原苑
- **法人名**: 社会福祉法人 磯子コスモス福祉会
- **理事長**: 鈴木 秀雄（関東学院大学名誉教授）
- **住所**: 〒235-0036 神奈川県横浜市磯子区中原3-6-10
- **TEL**: 045-776-3500（代表）/ 045-776-3100（ヘッダー電話ボタン用）
- **Email**: nakaharaen@muh.biglobe.ne.jp
- **デプロイ先**: Xserver

## デザイン決定履歴
| 日付 | 決定事項 |
|------|---------|
| 初期 | WordPress版のデザインをベースに、よりモダンに制作 |
| 初期 | カラー: コーラルピンク #E8837C をアクセントカラーに |
| 途中 | 見出しフォントを明朝体（Noto Serif JP）に変更 |
| 途中 | ヘッダー背景を赤 #E8365C に、ナビ文字を白に変更 |
| 途中 | 電話番号ボタンを緑 #76BF26 のピルボタンに変更 |
| 途中 | ヒーローのキャッチコピーを削除（動画のみ） |
| 途中 | ヘッダーロゴをテキストから logo.png 画像に変更 |
| 途中 | お問い合わせボタンをヒーロー右下に配置 |
| 途中 | お知らせセクションを画像付きカードグリッドにリデザイン |
| 途中 | 「最近の中原苑の動静・催事」を大きく目立つタイトルに |

## 管理画面仕様
- **PHP版**（admin/index.php + save.php）: Xserver本番用
- **HTMLデモ版**（admin/index.html）: GitHub Pagesプレビュー用（データ保存なし）
- **ログインパスワード**: `nakaharaen2026`（本番前に要変更）
- **機能**: 空き情報の数値更新 / お知らせの追加・削除
- **画像アップロード**: 未実装（将来対応予定）

## 動的コンテンツ（JSONから読み込み）
- **空き情報**（data/availability.json）: デイサービス空き人数、ショートステイ男女別空き人数
- **お知らせ**（data/news.json）: 日付・カテゴリ・タイトル・内容・画像・リンク

## 開発環境メモ
- PHPは未インストール → ローカル確認は `python3 -m http.server 8080`
- file:// プロトコルでは fetch() が動作しない（CORS制約）
- GitHub Pages へは gh-pages ブランチにプッシュして公開
- hero.mp4 は約15MBあるため Git push 時に注意

## 今後の作業
1. 下層ページ制作（施設紹介、サービス各ページ、講習案内、職員募集、セラエクサ、役員・評議員、お問い合わせフォーム）
2. 管理画面に画像アップロード機能を追加
3. Xserver への本番デプロイ
4. Basic認証（.htaccess）での管理画面保護
