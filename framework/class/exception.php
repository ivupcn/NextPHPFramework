<?php
/**
 *  exception.php NException 是 NextPHP 所有异常的基础类。
 *
 * @copyright			(C) 2005-2010 NextPHP
 * @license			http://ivup.cn/license/
 * @lastmodify			2013-03-03
 */

class NException extends Exception
{
	/**
     * 异常类型
     * @var string
     * @access private
     */
    private $type;

    // 是否存在多余调试信息
    private $extra;
	
    /**
     * 构造函数
     *
     * @param string $message 错误消息
     * @param int $code 错误代码
     */
    function __construct($message, $code = 0, $extra=false)
    {
        parent::__construct($message, $code);
		$this->type = get_class($this);
        $this->extra = $extra;
    }

    /**
     * 输出异常的详细信息和调用堆栈
     *
     * @code php
     * QException::dump($ex);
     * @endcode
     */
    static function dump(Exception $ex)
    {
        $out = get_class($ex);
        if ($ex->getMessage() != '')
        {
            $out .= " with message '" . $ex->getMessage() . "'";
        }

        $out .= ' in ' . $ex->getFile() . ':' . $ex->getLine() . "\n\n";
        $out .= $ex->getTraceAsString();

        if (ini_get('html_errors'))
        {
            echo nl2br(htmlspecialchars($out));
        }
        else
        {
            echo $out;
        }
    }
	
	/**
     * 异常输出 所有异常处理类均通过__toString方法输出错误
     * 每次异常都会写入系统日志
     * 该方法可以被子类重载
     * @access public
     * @return array
     */
    public function __toString() {
        $trace = $this->getTrace();
        if($this->extra)
		{
            // 通过throw_exception抛出的异常要去掉多余的调试信息
            array_shift($trace);
		}
        $this->class    =   isset($trace[0]['class'])?$trace[0]['class']:'';
        $this->function =   isset($trace[0]['function'])?$trace[0]['function']:'';
        $this->file     =   $trace[0]['file'];
        $this->line     =   $trace[0]['line'];
        $file           =   file($this->file);
        $traceInfo      =   '';
        $time = date('y-m-d H:i:m');
        foreach($trace as $t) {
            $traceInfo .= '['.$time.'] '.$t['file'].' ('.$t['line'].') ';
            $traceInfo .= $t['class'].$t['type'].$t['function'].'(';
            $traceInfo .= implode(', ', $t['args']);
            $traceInfo .=")\n";
        }
        $error['message']   = $this->message;
        $error['type']      = $this->type;
        $error['detail']    = L('_MODULE_').'['.MODULE_NAME.'] '.L('_ACTION_').'['.ACTION_NAME.']'."\n";
        $error['detail']   .=   ($this->line-2).': '.$file[$this->line-3];
        $error['detail']   .=   ($this->line-1).': '.$file[$this->line-2];
        $error['detail']   .=   '<font color="#FF6600" >'.($this->line).': <strong>'.$file[$this->line-1].'</strong></font>';
        $error['detail']   .=   ($this->line+1).': '.$file[$this->line];
        $error['detail']   .=   ($this->line+2).': '.$file[$this->line+1];
        $error['class']     =   $this->class;
        $error['function']  =   $this->function;
        $error['file']      = $this->file;
        $error['line']      = $this->line;
        $error['trace']     = $traceInfo;

        // 记录 Exception 日志
        if(C('LOG_EXCEPTION_RECORD')) {
            Log::Write('('.$this->type.') '.$this->message);
        }
        return $error ;
    }
}
?>