
#PDF—— maxin同学PHP GoodCooder 考题
## 考题内容
> 请使用php开发一个DIFF工具，实现对序列化后的数据（json、xml、yaml等）的对比功能，并把对比的结果存储与反馈。

# 程序运行

> php diff.php -t [json|xml] -l leftFile -r rightFile -o outFile -e utf8

## 参数说明

~~~
-t 待DIFF的数据/文件类型，测试题要求实现json/xml两种类型的DIFF功能
-l 待DIFF的左文件
-r 待DIFF的右文件
-o 存储对比结果的文件
-e 编码格式，支持gbk和utf8两种格式，默认为utf8
~~~
## 输入文件
> json格式：一条json数据仅占一行，两文件中相同行号的数据进行对比
> xml格式：每个文件就是一个完整的XML文件

> DIFF结果
	
* 存储到本地磁盘
格式：给出完整的diff结点路径，以及不同的内容，这块不做强制要求，只要能根据输出结果快速的定位到diff的位置（行、结点、内容）；
* //json diff示例
左文件：
~~~
{"format":"example1","content":[{"align":"center"}]}
{"format":"this is the same line"}
~~~
右文件：
~~~
{"format":"example2","content":[{"align":"left"}]}
{"format":"this is the same line"}
 ~~~
输出如下结果：
~~~
left line no: 2, right line no: 2。//分别输出文件行数
there are 2 diff[s] in 1 line[s], next is the detail differences.  //总共有几处diff，下面列出具体的diff内容
+++++++++++++
---line1:format
       > "example1"
       < "example2"
---line1:content->[0]->align
       > "center"
       < "left"
~~~
> 【解释】：输出diff的行号[lineno]以及具体的diff结点：format、content的第0个元素中的align存在diff
* xml diff示例
左文件：
```
<content>
    <ext>
         <phone>010-59928888</phone>
    </ext>
    <Name>百度大厦</Name>
</content>
```
右文件：
```
<content>
    <ext><phone>010 59928888</phone></ext>
    <name>百度大厦</name>
</content>
 ```
输出如下结果：
```
there are 3 diff[s], next is the detail differences.  //总共有几处diff，下面列出具体的diff内容
+++++++++++++
---content->ext->phone
       > 010-59928888
       < 010 59928888
---content->Name
       > 百度大厦
       < 
---content->name
       > 
       < 百度大厦
```
 【解释】：
总共存在3处diff：
content->ext->phone的值不同
左文件存在content->Name，而右文件中不存在
右文件存在content->name，而左文件中不存在


## 要求和注意事项

>- 需要支持命令行参数处理。具体包含：-h(帮助)、-v(版本)
>- 不论输入任何格式，程序不能出core；单行解析/DIFF失败，不能导致整个程序退出，需要在日志中
>- 记录下错误原因并继续
>- 当程序完成所有DIFF任务后，必须优雅退出
>- 代码严格遵守百度PHP编码规范（http://styleguide.baidu.com/style/php/index.html），可读性好
>- 代码具有高可维护性和可扩展性（比如增加一种数据类型的diff，支持2个以上文件的diff）。注意>- 模块、类、函数的设计和划分，要充分体现出申请人的编码能力
>- 完成相应的单元测试和使用demo。你的demo必须可运行，单元测试有效而且通过
>- 避免重复代码
>- 提交代码之前，请先使用eagle工具检查，工具在这里。
>- 禁止使用或抄袭已有的实现DIFF功能的PHP扩展
